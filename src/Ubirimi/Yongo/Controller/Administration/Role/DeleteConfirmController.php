<?php
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Permission\PermissionRole;

    Util::checkUserIsLoggedInAndRedirect();

    $permissionRoleId = $_GET['perm_role_id'];
    $role = PermissionRole::getPermissionRoleById($permissionRoleId);

    require_once __DIR__ . '/../../../Resources/views/administration/role/DeleteConfirm.php';