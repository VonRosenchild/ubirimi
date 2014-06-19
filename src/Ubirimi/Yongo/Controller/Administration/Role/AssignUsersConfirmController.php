<?php
    use Ubirimi\Repository\Client;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Permission\PermissionRole;

    Util::checkUserIsLoggedInAndRedirect();

    $permissionRoleId = $_GET['role_id'];
    $role = PermissionRole::getPermissionRoleById($permissionRoleId);
    
    $allUsers = Client::getUsers($clientId);
    $roleUsers = PermissionRole::getDefaultUsers($permissionRoleId);

    $role_users_arr_ids = array();
    while ($roleUsers && $user = $roleUsers->fetch_array(MYSQLI_ASSOC))
        $role_users_arr_ids[] = $user['user_id'];

    if ($roleUsers)
        $roleUsers->data_seek(0);

    require_once __DIR__ . '/../../../Resources/views/administration/role/AssignUsersConfirm.php';