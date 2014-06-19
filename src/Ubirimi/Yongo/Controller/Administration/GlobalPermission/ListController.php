<?php
    use Ubirimi\Repository\Client;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Permission\GlobalPermission;

    Util::checkUserIsLoggedInAndRedirect();

    $users = Client::getUsers($clientId);

    $globalPermissions = GlobalPermission::getAllByProductId(SystemProduct::SYS_PRODUCT_YONGO);
    $menuSelectedCategory = 'user';

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Global Permissions';

    require_once __DIR__ . '/../../../Resources/views/administration/global_permission/List.php';