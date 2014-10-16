<?php

    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $groups = $this->getRepository('ubirimi.user.group')->getByClientIdAndProductId($clientId, SystemProduct::SYS_PRODUCT_DOCUMENTADOR);

    $menuSelectedCategory = 'doc_users';

    require_once __DIR__ . '/../../../Resources/views/administration/group/List.php';