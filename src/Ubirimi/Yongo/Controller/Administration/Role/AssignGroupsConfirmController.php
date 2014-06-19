<?php
    use Ubirimi\Repository\Group\Group;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Permission\PermissionRole;

    Util::checkUserIsLoggedInAndRedirect();

    $permissionRoleId = $_GET['role_id'];
    $role = PermissionRole::getPermissionRoleById($permissionRoleId);
    
    $allGroups = Group::getByClientIdAndProductId($clientId, SystemProduct::SYS_PRODUCT_YONGO);
    $roleGroups = PermissionRole::getDefaultGroups($permissionRoleId);

    $role_groups_arr_ids = array();
    while ($roleGroups && $group = $roleGroups->fetch_array(MYSQLI_ASSOC))
        $role_groups_arr_ids[] = $group['group_id'];

    if ($roleGroups)
        $roleGroups->data_seek(0);

    require_once __DIR__ . '/../../../Resources/views/administration/role/AssignGroupsConfirm.php';