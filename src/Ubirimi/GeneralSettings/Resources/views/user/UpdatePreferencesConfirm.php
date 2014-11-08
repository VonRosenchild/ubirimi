<table width="100%">
    <tr>
        <td>Username</td>
        <td>
            <input type="text" class="inputText" disabled="disabled" value="<?php echo $settings['username'] ?>" ?>
        </td>
    </tr>
    <tr>
        <td>Email Address</td>
        <td>
            <input id="email_address"
                   type="text"
                   class="inputText"
                   value="<?php echo $settings['email'] ?>" ?>
            <div class="error" id="modal_user_preferences_email_error"></div>
        </td>
    </tr>
    <tr>
        <td width="280">Issues per page</td>
        <td>
            <input id="user_issues_per_page"
                   style="width: 60px"
                   class="inputText"
                   type="text"
                   value="<?php echo $settings['issues_per_page'] ?>"
                   name="issues_per_page" />
        </td>
    </tr>
    <tr>
        <td>Notify users of their own changes?</td>
        <td>
            <select id="user_notify_own_changes" class="select2InputSmall" style="width: 80px" name="notify_own_changes">
                <option <?php if ($settings['notify_own_changes_flag']) echo 'selected="selected"' ?> value="1">YES</option>
                <option <?php if (!$settings['notify_own_changes_flag']) echo 'selected="selected"' ?> value="0">NO</option>
            </select>
        </td>
    </tr>
    <tr>
        <td>Country</td>
        <td>
            <select name="user_country" id="user_country" class="select2InputMedium">
                <?php while ($country = $countries->fetch_array(MYSQLI_ASSOC)): ?>
                    <option <?php if ($country['id'] == $settings['country_id']) echo 'selected="selected"' ?> value="<?php echo $country['id'] ?>"><?php echo $country['name'] ?></option>
                <?php endwhile ?>
            </select>
        </td>
    </tr>
</table>