<?php

namespace Ubirimi\Documentador\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Documentador\Repository\Entity\Entity;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class UploadAttachmentController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientId = $session->get('client/id');
        $loggedInUserId = $session->get('user/id');

        $entityId = $request->get('id');
        $entity = $this->getRepository(Entity::class)->getById($entityId);

        $currentDate = Util::getServerCurrentDateTime();

        $pathBaseAttachments = Util::getAssetsFolder(SystemProduct::SYS_PRODUCT_DOCUMENTADOR, 'attachments');

        $index = 0;
        foreach ($_FILES['entity_upload_attachment']['name'] as $filename) {
            if (!empty($filename)) {
                // check if this file already exists
                $attachmentExists = $this->getRepository('documentador.entity.attachment')->getByEntityIdAndName($entityId, $filename);

                if ($attachmentExists) {
                    // get the last revision and increment it by one
                    $attachmentId = $attachmentExists['id'];
                    $revisions = $this->getRepository(Entity::class)->getRevisionsByAttachmentId($attachmentId);
                    $revisionNumber = $revisions->num_rows + 1;

                    // create the revision folder
                    mkdir($pathBaseAttachments . $entity['space_id'] . '/' . $entityId . '/' . $attachmentId . '/' . $revisionNumber);
                } else {
                    // add the file to the list of files
                    $attachmentId = $this->getRepository('documentador.entity.attachment')->add($entityId, $filename, $currentDate);

                    $this->getRepository(UbirimiLog::class)->add($clientId, SystemProduct::SYS_PRODUCT_DOCUMENTADOR, $loggedInUserId, 'ADD Documentador entity attachment ' . $filename, $currentDate);

                    $revisionNumber = 1;

                    // create the folder for the file
                    if (!is_dir($pathBaseAttachments . $entity['space_id'])) {
                        mkdir($pathBaseAttachments . $entity['space_id']);
                    }

                    if (!is_dir($pathBaseAttachments . $entity['space_id']) . '/' . $entityId) {
                        mkdir($pathBaseAttachments . $entity['space_id'] . '/' . $entityId);
                    }

                    // create the folder for the first revision
                    mkdir($pathBaseAttachments . $entity['space_id'] . '/' . $entityId . '/' . $attachmentId);
                    mkdir($pathBaseAttachments . $entity['space_id'] . '/' . $entityId . '/' . $attachmentId . '/' . $revisionNumber);
                }

                // add revision to the file

                $this->getRepository('documentador.entity.attachment')->addRevision($attachmentId, $loggedInUserId, $currentDate);

                if ($revisionNumber > 1) {
                    $this->getRepository(UbirimiLog::class)->add($clientId, SystemProduct::SYS_PRODUCT_DOCUMENTADOR, $loggedInUserId, 'ADD Documentador entity attachment revision to ' . $filename, $currentDate);
                }

                $baseFileName = pathinfo($filename, PATHINFO_FILENAME);
                $extension = pathinfo($filename, PATHINFO_EXTENSION);

                move_uploaded_file($_FILES["entity_upload_attachment"]["tmp_name"][$index], $pathBaseAttachments . $entity['space_id'] . '/' . $entityId . '/' . $attachmentId . '/' . $revisionNumber . '/' . $baseFileName . '.' . $extension);

                if ($revisionNumber > 1) {
                    // update all existing links to this attachment
                    $oldLink = '/assets/documentador/attachment/' . $entity['space_id'] . '/' . $entityId . '/' . $attachmentId . '/' . ($revisionNumber - 1) . '/' . $baseFileName . '.' . $extension;
                    $newLink = '/assets/documentador/attachment/' . $entity['space_id'] . '/' . $entityId . '/' . $attachmentId . '/' . $revisionNumber . '/' . $baseFileName . '.' . $extension;

                    $content = $entity['content'];
                    $content = str_replace($oldLink, $newLink, $content);
                    $this->getRepository(Entity::class)->updateContent($entityId, $content);
                }

                $index++;
            }
        }

        return new RedirectResponse('/documentador/page/attachments/' . $entityId);
    }
}