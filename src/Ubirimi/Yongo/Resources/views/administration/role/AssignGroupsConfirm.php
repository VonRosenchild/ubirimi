<div class="infoBox">NOTE: When a new project is created, it will be assigned these 'default members' for the '<?php echo $role['name'] ?>' project role.
    <br />Note that 'default members' apply only when a project is created.
    <br />Changing the 'default members' for a project role will not affect role membership for existing projects. </div>
<span>Permission role <?php echo $role['name'] ?></span>
<hr />
<table align="center">
    <tr>
        <td>Available Groups</td>
        <td></td>
        <td>Permission role default groups</td>
    </tr>
    <tr>
        <td>
            <select name="all_groups" size="10" id="all_groups" class="inputTextCombo">
                <?php while ($group = $allGroups->fetch_array(MYSQLI_ASSOC)): ?>
                    <?php if (array_search($group['id'], $role_groups_arr_ids) === false): ?>
                        <option value="<?php echo $group['id'] ?>"><?php echo $group['name'] ?></option>
                    <?php endif ?>
                <?php endwhile ?>
            </select>
        </td>
        <td align="center" style="vertical-align: middle">
            <a id="assign_group_btn" href="#" class="btn ubirimi-btn">&nbsp;<img border="0" height="10" src="/img/br_next.png" alt=""/>&nbsp;</a>
            <div></div>
            <a id="remove_group_btn" href="#" class="btn ubirimi-btn">&nbsp;<img border="0" height="10" src="/img/br_prev.png" alt=""/>&nbsp;</a>
        </td>
        <td>
            <select name="assigned_groups" size="10" id="assigned_groups" class="inputTextCombo">
                <?php while ($roleGroups && $group = $roleGroups->fetch_array(MYSQLI_ASSOC)): ?>
                    <option value="<?php echo $group['group_id'] ?>"><?php echo $group['group_name'] ?></option>
                <?php endwhile ?>
            </select>
        </td>
    </tr>
</table>
<input type="hidden" value="<?php echo $permissionRoleId; ?>" id="role_id" />