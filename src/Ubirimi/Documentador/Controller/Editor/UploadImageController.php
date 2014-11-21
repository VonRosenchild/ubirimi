<?php

/*
 *  Copyright (C) 2012-2014 SC Ubirimi SRL <info-copyright@ubirimi.com>
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License version 2 as
 *  published by the Free Software Foundation.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301, USA.
 */

namespace Ubirimi\Documentador\Controller\Editor;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Documentador\Repository\Entity\Entity;
use Ubirimi\Documentador\Repository\Entity\EntityAttachment;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class UploadImageController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $loggedInUserId = $session->get('user/id');

        $CKEditorFuncNum = $_GET['CKEditorFuncNum'];
        $allowedExtensions = array("gif", "jpeg", "jpg", "png");

        $extension = strtolower(pathinfo($_FILES['upload']['name'], PATHINFO_EXTENSION));

        $entityId = $session->get('current_edit_entity_id');
        $entity = $this->getRepository(Entity::class)->getById($entityId);
        $spaceId = $entity['space_id'];

        $currentDate = Util::getServerCurrentDateTime();
        $attachmentsBaseFilePath = Util::getAssetsFolder(SystemProduct::SYS_PRODUCT_DOCUMENTADOR, 'attachments');
        if ((($_FILES["upload"]["type"] == "image/gif")
                || ($_FILES["upload"]["type"] == "image/jpeg")
                || ($_FILES["upload"]["type"] == "image/jpg")
                || ($_FILES["upload"]["type"] == "image/pjpeg")
                || ($_FILES["upload"]["type"] == "image/x-png")
                || ($_FILES["upload"]["type"] == "image/png"))
            && in_array($extension, $allowedExtensions)
        ) {
            if ($_FILES["upload"]["error"] > 0) {
                // error

            } else {
                $fileName = $_FILES["upload"]["name"];

                // check if the attachment already exists
                $attachment = $this->getRepository(EntityAttachment::class)->getByEntityIdAndName($entityId, $fileName);
                if ($attachment) {
                    // get the last revision and increment it by one
                    $attachmentId = $attachment['id'];
                    $revisions = $this->getRepository(Entity::class)->getRevisionsByAttachmentId($attachmentId);
                    $revisionNumber = $revisions->num_rows + 1;

                    // create the revision folder
                    mkdir($attachmentsBaseFilePath . $spaceId . '/' . $entityId. '/' . $attachmentId . '/' . $revisionNumber);

                } else {
                    // add the attachment in the database
                    $currentDate = Util::getServerCurrentDateTime();
                    $attachmentId = $this->getRepository(EntityAttachment::class)->add($entityId, $fileName, $currentDate);

                    $revisionNumber = 1;

                    if (!file_exists($attachmentsBaseFilePath . $spaceId)) {
                        mkdir($attachmentsBaseFilePath . $spaceId);
                    }

                    if (!file_exists($attachmentsBaseFilePath . $spaceId . '/' . $entityId)) {
                        mkdir($attachmentsBaseFilePath . $spaceId . '/' . $entityId);
                    }

                    if (!file_exists($attachmentsBaseFilePath . $spaceId . '/' . $entityId . '/' . $attachmentId)) {
                        mkdir($attachmentsBaseFilePath . $spaceId . '/' . $entityId . '/' . $attachmentId);
                    }

                    mkdir($attachmentsBaseFilePath . $spaceId . '/' . $entityId . '/' . $attachmentId . '/' . $revisionNumber);
                }

                $directoryName = $attachmentsBaseFilePath . $spaceId . '/' . $entityId. '/' . $attachmentId . '/' . $revisionNumber;
                move_uploaded_file($_FILES["upload"]["tmp_name"], $directoryName . '/' . $fileName);

                $this->getRepository(EntityAttachment::class)->addRevision($attachmentId, $loggedInUserId, $currentDate);

                $attachmentsPath = UbirimiContainer::get()['asset.documentador_entity_attachments'];
                $html = '<html><body><script type="text/javascript">window.parent.CKEDITOR.tools.callFunction("' . $CKEditorFuncNum . '", "/assets/' . $attachmentsPath . $spaceId . '/' . $entityId. '/' . $attachmentId . '/' . $revisionNumber . '/' . $fileName . '");</script></body></html>';

                return new Response($html);
            }

        } else {
            return new Response('');
        }
    }
}