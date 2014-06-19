<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Permission\PermissionRole;

    Util::checkUserIsLoggedInAndRedirect();

    $permissionRoleId = $_POST['role_id'];
    $userArray = $_POST['user_arr'];

    $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));
    $permissionRole = PermissionRole::getById($permissionRoleId);
    PermissionRole::deleteDefaultUsersByPermissionRoleId($permissionRoleId);
    PermissionRole::addDefaultUsers($permissionRoleId, $userArray, $currentDate);

    Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'UPDATE Yongo Project Role ' . $permissionRole['name'] . ' Definition', $currentDate);