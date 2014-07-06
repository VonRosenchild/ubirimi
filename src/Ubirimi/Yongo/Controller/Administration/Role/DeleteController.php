<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Permission\PermissionRole;

    Util::checkUserIsLoggedInAndRedirect();

    $permissionRoleId = $_POST['perm_role_id'];

    $permissionRole = PermissionRole::getById($permissionRoleId);
    PermissionRole::deleteById($permissionRoleId);

    $date = Util::getServerCurrentDateTime();
    Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'DELETE Yongo Project Role ' . $permissionRole['name'], $date);