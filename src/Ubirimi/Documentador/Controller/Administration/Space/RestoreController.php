<?php

    use Ubirimi\Repository\Documentador\Entity;
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $pageId = $_POST['id'];
    $entity = Entity::getById($pageId);

    Entity::restoreById($pageId);

    $currentDate = Util::getServerCurrentDateTime();

    Log::add($clientId, SystemProduct::SYS_PRODUCT_DOCUMENTADOR, $loggedInUserId, 'RESTORE Documentador entity ' . $entity['name'], $currentDate);