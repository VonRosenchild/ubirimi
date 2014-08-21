<table>
    <tr>
        <td>
            <div>User</div>
            <select style="width: 100%;" name="user_to_share[]" id="user_to_share" class="select2Input" multiple="multiple">
                <?php while ($user = $users->fetch_array(MYSQLI_ASSOC)): ?>
                    <?php if ($user['id'] != $loggedInUserId): ?>
                        <option value="<?php echo $user['id'] ?>"><?php echo $user['first_name'] . ' ' . $user['last_name'] ?></option>
                    <?php endif ?>
                <?php endwhile ?>
            </select>
            <div class="error" id="share_no_user_selected"></div>
        </td>
    </tr>
    <tr>
        <td>
            <div>Note</div>
            <textarea id="share_event_note" rows="20" class="inputTextAreaLarge" style="height: 400px"></textarea>
        </td>
    </tr>
</table>