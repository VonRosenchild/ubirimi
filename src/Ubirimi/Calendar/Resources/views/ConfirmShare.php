<table>
    <tr>
        <td>
            <div>Share with</div>
            <select style="width: 100%;" name="user_to_share[]" id="user_to_share" class="select2Input" multiple="multiple">
                <?php while ($users && $user = $users->fetch_array(MYSQLI_ASSOC)): ?>
                    <?php if ($user['id'] != $loggedInUserId): ?>
                        <?php $found = false ?>
                        <?php if ($sharedWithUsers): ?>
                            <?php foreach ($sharedWithUsers as $sharedUser): ?>
                                <?php if ($sharedUser['id'] == $user['id']): ?>
                                    <?php
                                        $found = true;
                                        break;
                                    ?>
                                <?php endif ?>
                            <?php endforeach ?>
                        <?php endif ?>
                        <option <?php if ($found) echo 'selected="selected"' ?> value="<?php echo $user['id'] ?>"><?php echo $user['first_name'] . ' ' . $user['last_name'] ?></option>

                    <?php endif ?>
                <?php endwhile ?>
            </select>
            <div class="error" id="share_no_user_selected"></div>
        </td>
    </tr>
    <tr>
        <td>
            <div>Note</div>
            <textarea id="share_calendar_note" rows="10" class="inputTextAreaLarge"></textarea>
        </td>
    </tr>
</table>