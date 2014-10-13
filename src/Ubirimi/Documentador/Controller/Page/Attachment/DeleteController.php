<?php
    use Ubirimi\Repository\Documentador\Entity;
    use Ubirimi\Repository\Documentador\EntityAttachment;
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $attachmentId = $_POST['id'];
    $attachment = EntityAttachment::getById($attachmentId);
    $entityId = $attachment['documentator_entity_id'];
    $space = Entity::getById($entityId);
    $spaceId = $space['space_id'];
    $currentDate = Util::getServerCurrentDateTime();

    EntityAttachment::deleteById($spaceId, $entityId, $attachmentId);

    $this->getRepository('ubirimi.general.log')->add($clientId, SystemProduct::SYS_PRODUCT_DOCUMENTADOR, $loggedInUserId, 'DELETE Documentador entity attachment ' . $attachment['name'], $currentDate);

    $attachments = EntityAttachment::getByEntityId($entityId);
    if (!$attachments) {
        // delete the attachment folder
        $attachmentsFilePath = Util::getAssetsFolder(SystemProduct::SYS_PRODUCT_DOCUMENTADOR, 'attachments');
        Util::deleteDir($attachmentsFilePath . $spaceId . '/' . $entityId);
    }