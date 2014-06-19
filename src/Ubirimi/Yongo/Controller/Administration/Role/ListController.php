<?php
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();
    $menuSelectedCategory = 'user';

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Roles';

    require_once __DIR__ . '/../../../Resources/views/administration/role/List.php';