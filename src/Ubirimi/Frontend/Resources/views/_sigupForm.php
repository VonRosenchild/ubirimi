
    <?php if ($session->has('user_account_created')): ?>
    <div class="global-msg confirmation">
        Done. Check your email in a few minutes for further details.
    </div>
    <?php endif ?>

    <div class="form-section clearfix sectionFeature blue" style="width: 300px">
        <fieldset>
            <label>Username <span class="mandatory">*</label>

            <input class="inputTextTall" type="text" value="<?php if (isset($username)) echo $username ?>" name="username"/>
            <?php if ($errors['empty_username']): ?>
                <div class="HPFieldLabel14Red">The username can not be empty.</div>
            <?php elseif ($errors['invalid_username']): ?>
                <div class="HPFieldLabel14Red">The username is not valid.</div>
            <?php elseif ($errors['duplicate_username']): ?>
                <div class="HPFieldLabel14Red">The username is not available.</div>
            <?php endif ?>
        </fieldset>

        <fieldset>
            <label>Password <span class="mandatory">*</label>

            <input class="inputTextTall" type="password" value="<?php if (isset($password)) echo $password ?>" name="password"/>
            <?php if ($errors['empty_password']): ?>
                <div class="HPFieldLabel14Red">The password can not be empty.</div>
            <?php endif ?>
        </fieldset>

        <fieldset>
            <label>Confirm Password <span class="mandatory">*</label>

            <input class="inputTextTall" type="password" value="<?php if (isset($passwordAgain)) echo $passwordAgain ?>" name="password_again"/>
            <?php if ($errors['password_mismatch']): ?>
                <div class="HPFieldLabel14Red">The passwords do not match.</div>
            <?php endif ?>
        </fieldset>

        <fieldset>
            <label>Email address <span class="mandatory">*</label>

            <input class="inputTextTall" type="text" value="<?php if (isset($email)) echo $email ?>" name="email"/>
            <?php if ($errors['empty_email']): ?>
                <div class="HPFieldLabel14Red">The email address can not be empty.</div>
            <?php elseif ($errors['email_not_valid']): ?>
                <div class="HPFieldLabel14Red">The email address is not valid.</div>
            <?php elseif ($errors['email_already_exists']): ?>
                <div class="HPFieldLabel14Red">The email address is already assigned to another user.</div>
            <?php endif ?>
        </fieldset>

        <fieldset>
            <label>First name <span class="mandatory">*</label>

            <input class="inputTextTall" type="text" name="first_name" value="<?php if (isset($firstName)) echo $firstName ?>"/>
            <?php if ($errors['empty_first_name']): ?>
                <div class="HPFieldLabel14Red">The first name can not be empty.</div>
            <?php endif ?>
        </fieldset>

        <fieldset>
            <label>Last name <span class="mandatory">*</label>
            <input class="inputTextTall" type="text" name="last_name" value="<?php if (isset($lastName)) echo $lastName ?>"/>
            <?php if ($errors['empty_last_name']): ?>
                <div class="HPFieldLabel14Red">The last name can not be empty.</div>
            <?php endif ?>
        </fieldset>

        <fieldset>
            <label>Country <span class="mandatory">*</label>
            <select name="country" class="select2InputSmall">
                <?php while ($country = $countries->fetch_array(MYSQLI_ASSOC)): ?>
                    <option value="<?php echo $country['id'] ?>"><?php echo $country['name'] ?></option>
                <?php endwhile ?>
            </select>
        </fieldset>

    </div>