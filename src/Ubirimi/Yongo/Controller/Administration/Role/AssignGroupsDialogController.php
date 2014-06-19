<?php
    use Ubirimi\Repository\Group\Group;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Permission\PermissionRole;
    use Ubirimi\Yongo\Repository\Project\Project;

    Util::checkUserIsLoggedInAndRedirect();

    $permissionRoleId = $_GET['role_id'];
    $projectId = $_GET['project_id'];
    $role = PermissionRole::getPermissionRoleById($permissionRoleId);
    
    $all_groups = Group::getByClientIdAndProductId($clientId, SystemProduct::SYS_PRODUCT_YONGO);
    $role_groups = Project::getGroupsInRole($projectId, $permissionRoleId);

    $role_groups_arr_ids = array();
    while ($role_groups && $group = $role_groups->fetch_array(MYSQLI_ASSOC))
        $role_groups_arr_ids[] = $group['group_id'];

    if ($role_groups) {
        $role_groups->data_seek(0);
    }
?>
<span>Permission role <?php echo $role['name'] ?></span>
<hr />
<table align="center">
    <tr>
        <td>Available Groups</td>
        <td></td>
        <td>Permission Role Groups</td>
    </tr>
    <tr>
        <td>
            <?php $all_groups->data_seek(0); ?>
            <select name="all_groups" size="10" id="all_groups" class="inputTextCombo">
                <?php while ($group = $all_groups->fetch_array(MYSQLI_ASSOC)): ?>
                    <?php if (array_search($group['id'], $role_groups_arr_ids) === false): ?>
                        <option value="<?php echo $group['id'] ?>"><?php echo $group['name'] ?></option>
                    <?php endif ?>
                <?php endwhile ?>
            </select>
        </td>
        <td align="center">
            <a id="assign_group_btn" href="#" class="btn ubirimi-btn">&nbsp;<img border="0" height="10" src="/img/br_next.png" alt=""/>&nbsp;</a>
            <div></div>
            <a id="remove_group_btn" href="#" class="btn ubirimi-btn">&nbsp;<img border="0" height="10" src="/img/br_prev.png" alt=""/>&nbsp;</a>
        </td>
        <td valign="top">
            <select name="assigned_groups" size="10" id="assigned_groups" class="inputTextCombo">
                <?php while ($role_groups && $group = $role_groups->fetch_array(MYSQLI_ASSOC)): ?>
                    <option value="<?php echo $group['group_id'] ?>"><?php echo $group['group_name'] ?></option>
                <?php endwhile ?>
            </select>
        </td>
    </tr>
</table>
<input type="hidden" value="<?php echo $permissionRoleId; ?>" id="role_id" />