<span>User <?php echo $user['first_name'] . ' ' . $user['last_name'] ?></span>
<br />
<table align="center">
    <tr>
        <td>Available groups</td>
        <td></td>
        <td>User assigned groups</td>
    </tr>
    <tr>
        <td>
            <select name="all_groups" size="10" id="all_groups" class="inputTextCombo">
                <?php while ($allProductGroups && $group = $allProductGroups->fetch_array(MYSQLI_ASSOC)): ?>
                    <?php if (array_search($group['id'], $user_groups_ids_arr) === false): ?>
                        <option <?php if ($firstSelected) { echo 'selected="selected"'; $firstSelected = false; } ?> value="<?php echo $group['id'] ?>"><?php echo $group['name'] ?></option>
                    <?php endif ?>
                <?php endwhile ?>
            </select>
        </td>
        <td align="center" style="vertical-align: middle">
            <a id="assign_group_user_btn" style="text-align: center;" href="#" class="btn ubirimi-btn">&nbsp;&nbsp;<img border="0" height="10" src="/img/br_next.png" alt=""/>&nbsp;&nbsp;</a>
            <div></div>
            <a id="remove_group_user_btn" style="text-align: center;" href="#" class="btn ubirimi-btn">&nbsp;&nbsp;<img border="0" height="10" src="/img/br_prev.png" alt=""/>&nbsp;&nbsp;</a>
        </td>
        <td>
            <select name="assigned_groups" size="10" id="assigned_groups" class="inputTextCombo">
                <?php while ($userGroups && $group = $userGroups->fetch_array(MYSQLI_ASSOC)): ?>
                    <option value="<?php echo $group['id'] ?>"><?php echo $group['name'] ?></option>
                <?php endwhile ?>
            </select>
        </td>
    </tr>
</table>