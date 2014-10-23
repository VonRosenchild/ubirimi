<div>
    <table width="100%" class="modal-table">
        <tr>
            <td colspan="2">Change password for user <?php echo $user['first_name'] . ' ' . $user['last_name'] ?> in repository <?php echo $svnRepo['name'] ?></td>
        </tr>
        <tr>
            <td valign="top">Password <span class="mandatory">*</span></td>
            <td>
                <input class="inputText" type="password" id="password" value="<?php if (isset($password)) echo $password ?>" name="password" />
                <?php if ($errors['empty_password']): ?>
                    <div class="error">The password can not be empty.</div>
                <?php endif ?>
            </td>
        </tr>
        <tr>
            <td valign="top">Confirm Password <span class="mandatory">*</span></td>
            <td>
                <input class="inputText" type="password" id="password_again" value="<?php if (isset($passwordAgain)) echo $passwordAgain ?>" name="password_again" />
                <?php if ($errors['password_mismatch']): ?>
                    <div class="error">The passwords do not match.</div>
                <?php endif ?>
            </td>
        </tr>
    </table>
</div>