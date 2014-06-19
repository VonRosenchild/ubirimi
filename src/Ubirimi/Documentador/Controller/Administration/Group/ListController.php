<?php
    use Ubirimi\Repository\Group\Group;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $groups = Group::getByClientIdAndProductId($clientId, SystemProduct::SYS_PRODUCT_DOCUMENTADOR);

    $menuSelectedCategory = 'doc_users';

    require_once __DIR__ . '/../../../Resources/views/administration/group/List.php';