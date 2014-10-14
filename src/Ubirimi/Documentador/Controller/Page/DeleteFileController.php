<?php


    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $fileId = $_POST['id'];

    $file = Entity::getFileById($fileId);
    $entityId = $file['documentator_entity_id'];

    Entity::deleteFileById($entityId, $fileId);

    $currentDate = Util::getServerCurrentDateTime();
    $this->getRepository('ubirimi.general.log')->add($clientId, SystemProduct::SYS_PRODUCT_DOCUMENTADOR, $loggedInUserId, 'DELETE Documentador file ' . $file['name'], $currentDate);