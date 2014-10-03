<span>Group <?php echo $group['name'] ?></span>
<br />
<table align="center">
    <tr>
        <td>Available users</td>
        <td></td>
        <td>Group assigned users</td>
    </tr>
    <tr>
        <td>
            <?php $allUsers->data_seek(0); ?>
            <select name="all_users" size="10" id="all_users" class="inputTextCombo">
                <?php while ($user = $allUsers->fetch_array(MYSQLI_ASSOC)): ?>
                    <?php if (array_search($user['id'], $groupUsersArrayIds) === false): ?>
                        <option <?php if ($firstSelected) { echo 'selected="selected"'; $firstSelected = false; } ?> value="<?php echo $user['id'] ?>"><?php echo $user['first_name'] . ' ' . $user['last_name'] ?></option>
                    <?php endif ?>
                <?php endwhile ?>
            </select>
        </td>
        <td align="center" style="vertical-align: middle">
            <a id="assign_user_btn" style="text-align: center;" href="#" class="btn ubirimi-btn">&nbsp;<img border="0" height="10" src="/img/br_next.png" alt=""/>&nbsp;</a>
            <div></div>
            <a id="remove_user_btn" style="text-align: center;" href="#" class="btn ubirimi-btn">&nbsp;<img border="0" height="10" src="/img/br_prev.png" alt=""/>&nbsp;</a>
        </td>
        <td>
            <select name="assigned_users" size="10" id="assigned_users" class="inputTextCombo">
                <?php while ($groupUsers && $user = $groupUsers->fetch_array(MYSQLI_ASSOC)): ?>
                    <option value="<?php echo $user['user_id'] ?>"><?php echo $user['first_name'] . ' ' . $user['last_name'] ?></option>
                <?php endwhile ?>
            </select>
        </td>
    </tr>
</table>
<input type="hidden" value="<?php echo $groupId; ?>" id="group_id" />