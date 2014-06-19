<?php
    use Ubirimi\Repository\Documentador\Entity;
    use Ubirimi\Repository\Documentador\EntityAttachment;
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $entityId = $_GET['id'];
    $entity = Entity::getById($entityId);

    $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));

    $pathBaseAttachments = Util::getAssetsFolder(SystemProduct::SYS_PRODUCT_DOCUMENTADOR, 'attachments');

    $index = 0;
    foreach ($_FILES['entity_upload_attachment']['name'] as $filename) {
        if (!empty($filename)) {
            // check if this file already exists
            $attachmentExists = EntityAttachment::getByEntityIdAndName($entityId, $filename);

            if ($attachmentExists) {
                // get the last revision and increment it by one
                $attachmentId = $attachmentExists['id'];
                $revisions = Entity::getRevisionsByAttachmentId($attachmentId);
                $revisionNumber = $revisions->num_rows + 1;

                // create the revision folder
                mkdir($pathBaseAttachments . $entity['space_id'] . '/' . $entityId . '/' . $attachmentId . '/' . $revisionNumber);
            } else {
                // add the file to the list of files
                $attachmentId = EntityAttachment::add($entityId, $filename, $currentDate);

                Log::add($clientId, SystemProduct::SYS_PRODUCT_DOCUMENTADOR, $loggedInUserId, 'ADD Documentador entity attachment ' . $filename, $currentDate);

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

            EntityAttachment::addRevision($attachmentId, $loggedInUserId, $currentDate);

            if ($revisionNumber > 1) {
                Log::add($clientId, SystemProduct::SYS_PRODUCT_DOCUMENTADOR, $loggedInUserId, 'ADD Documentador entity attachment revision to ' . $filename, $currentDate);
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
                Entity::updateContent($entityId, $content);
            }

            $index++;
        }
    }

    header('Location: /documentador/page/attachments/' . $entityId);