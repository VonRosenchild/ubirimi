<?php
    use Ubirimi\Repository\Group\Group;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $groups = Group::getByClientIdAndProductId($clientId, SystemProduct::SYS_PRODUCT_YONGO);

    $menuSelectedCategory = 'user';

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Groups';

    require_once __DIR__ . '/../../../Resources/views/administration/group/List.php';