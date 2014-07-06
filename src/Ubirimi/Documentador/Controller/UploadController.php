<?php
    use Ubirimi\Repository\Documentador\Entity;
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

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

                    Log::add($clientId, SystemProduct::SYS_PRODUCT_DOCUMENTADOR, $loggedInUserId, 'ADD Documentador entity file ' . $filename, $currentDate);

                    $revisionNumber = 1;

                    // create the folder for the file
                    mkdir($pathBaseAttachments . $entityId . '/' . $fileId);

                    // create the folder for the first revision
                    mkdir($pathBaseAttachments . $entityId . '/' . $fileId . '/' . $revisionNumber);
                }

                // add revision to the file

                Entity::addFileRevision($fileId, $loggedInUserId, $currentDate);

                if ($revisionNumber > 1) {
                    Log::add($clientId, SystemProduct::SYS_PRODUCT_DOCUMENTADOR, $loggedInUserId, 'ADD Documentador entity file revision to ' . $filename, $currentDate);
                }
                $baseFileName = pathinfo($filename, PATHINFO_FILENAME);
                $extension = pathinfo($filename, PATHINFO_EXTENSION);

                move_uploaded_file($_FILES["entity_upload_file"]["tmp_name"][$index], $pathBaseAttachments . $entityId . '/' . $fileId . '/' . $revisionNumber . '/' . $baseFileName . '.' . $extension);
                $index++;
            }
        }
    }

    header('Location: /documentador/page/view/' . $entityId);