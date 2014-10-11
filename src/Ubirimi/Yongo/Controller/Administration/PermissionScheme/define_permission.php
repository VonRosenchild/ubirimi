<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Permission\Scheme;

    Util::checkUserIsLoggedInAndRedirect();

    $permissionId = $_POST['perm_id'];
    $permissionSchemeId = $_POST['permission_scheme_id'];
    $userArray = $_POST['user_arr'];
    $user_group_arr = $_POST['user_group_arr'];
    $perm_roles_arr = $_POST['perm_roles_arr'];

    $currentDate = Util::getServerCurrentDateTime();
    $somethingChanged = false;

    if ($userArray != -1) {
        Scheme::deleteUserDataByPermissionId($permissionSchemeId, $permissionId);
        Scheme::addUserDataToPermissionId($permissionSchemeId, $permissionId, $userArray);
        $somethingChanged = true;
    }

    if ($user_group_arr != -1) {
        Scheme::deleteGroupDataByPermissionId($permissionSchemeId, $permissionId);
        Scheme::addGroupDataToPermissionId($permissionSchemeId, $permissionId, $user_group_arr);
        $somethingChanged = true;
    }

    if ($perm_roles_arr != -1) {
        Scheme::deleteRoleDataByPermissionId($permissionSchemeId, $permissionId);
        Scheme::addRoleDataToPermissionId($permissionSchemeId, $permissionId, $perm_roles_arr, $currentDate);
        $somethingChanged = true;
    }

    Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'ADD Yongo Permission Scheme Data', $currentDate);