<?php
    use Ubirimi\Repository\Documentador\Entity;
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $fileId = $_POST['id'];

    $file = Entity::getFileById($fileId);
    $entityId = $file['documentator_entity_id'];

    Entity::deleteFileById($entityId, $fileId);

    $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));
    Log::add($clientId, SystemProduct::SYS_PRODUCT_DOCUMENTADOR, $loggedInUserId, 'DELETE Documentador file ' . $file['name'], $currentDate);