<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Permission\PermissionRole;

    Util::checkUserIsLoggedInAndRedirect();

    $permissionRoleId = $_POST['role_id'];
    $groupArrayIds = $_POST['group_arr'];

    $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));
    $permissionRole = PermissionRole::getById($permissionRoleId);
    PermissionRole::deleteDefaultGroupsByPermissionRoleId($permissionRoleId);
    PermissionRole::addDefaultGroups($permissionRoleId, $groupArrayIds, $currentDate);

    Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'UPDATE Yongo Project Role ' . $permissionRole['name'] . ' Definition', $currentDate);