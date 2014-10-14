<?php

    

    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $pageId = $_POST['id'];
    $entity = Entity::getById($pageId);

    Entity::restoreById($pageId);

    $currentDate = Util::getServerCurrentDateTime();

    $this->getRepository('ubirimi.general.log')->add($clientId, SystemProduct::SYS_PRODUCT_DOCUMENTADOR, $loggedInUserId, 'RESTORE Documentador entity ' . $entity['name'], $currentDate);