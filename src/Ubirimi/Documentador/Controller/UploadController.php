<?php

namespace Ubirimi\Documentador\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Documentador\Repository\Space\Space;
use Ubirimi\Documentador\Repository\Entity\Entity;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class UploadController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $entityId = $_GET['id'];
        $currentDate = Util::getServerCurrentDateTime();
        $pathBaseAttachments = Util::getAssetsFolder(SystemProduct::SYS_PRODUCT_DOCUMENTADOR, 'filelists');
        $index = 0;

        if (isset($_FILES['entity_upload_file'])) {
            foreach ($_FILES['entity_upload_file']['name'] as $filename) {
                if (!empty($filename)) {
                    // check if this file already exists
                    $fileExists = Entity::getFileByName($entityId, $filename);

                    if ($fileExists) {
                        // get the last revision and increment it by one
                        $fileId = $fileExists['id'];
                        $revisions = Entity::getRevisionsByFileId($fileId);
                        $revisionNumber = $revisions->num_rows + 1;

                        // create the revision folder
                        if (!file_exists($pathBaseAttachments . $entityId)) {
                            mkdir($pathBaseAttachments . $entityId);
                        }
                        if (!file_exists($pathBaseAttachments . $entityId . '/' . $fileId)) {
                            mkdir($pathBaseAttachments . $entityId . '/' . $fileId);
                        }
                        if (!file_exists($pathBaseAttachments . $entityId . '/' . $fileId . '/' . $revisionNumber)) {
                            mkdir($pathBaseAttachments . $entityId . '/' . $fileId . '/' . $revisionNumber);
                        }

                    } else {
                        // add the file to the list of files
                        $fileId = Entity::addFile($entityId, $filename, $currentDate);

                        $this->getRepository('ubirimi.general.log')->add($clientId, SystemProduct::SYS_PRODUCT_DOCUMENTADOR, $loggedInUserId, 'ADD Documentador entity file ' . $filename, $currentDate);

                        $revisionNumber = 1;

                        // create the folder for the file
                        mkdir($pathBaseAttachments . $entityId . '/' . $fileId);

                        // create the folder for the first revision
                        mkdir($pathBaseAttachments . $entityId . '/' . $fileId . '/' . $revisionNumber);
                    }

                    // add revision to the file

                    Entity::addFileRevision($fileId, $loggedInUserId, $currentDate);

                    if ($revisionNumber > 1) {
                        $this->getRepository('ubirimi.general.log')->add($clientId, SystemProduct::SYS_PRODUCT_DOCUMENTADOR, $loggedInUserId, 'ADD Documentador entity file revision to ' . $filename, $currentDate);
                    }
                    $baseFileName = pathinfo($filename, PATHINFO_FILENAME);
                    $extension = pathinfo($filename, PATHINFO_EXTENSION);

                    move_uploaded_file($_FILES["entity_upload_file"]["tmp_name"][$index], $pathBaseAttachments . $entityId . '/' . $fileId . '/' . $revisionNumber . '/' . $baseFileName . '.' . $extension);
                    $index++;
                }
            }
        }

        header('Location: /documentador/page/view/' . $entityId);
    }
}