<form class="standard-form horizontal" method="post" name="client-sign-up" autocomplete="off" action="/sign-up">
    <?php if ($session->has('client_account_created')): ?>
    <div class="global-msg confirmation">
        Done. Check your email in a few minutes for further details.
    </div>
    <?php endif ?>

    <div class="form-section clearfix">
        <h3>1. On Demand URL</h3>
        <div class="section-left align-left sectionFeature blue">
            <fieldset>
                <label for="domain">Company domain</label>
                <div class="field-extension-container">
                    <span class="field-extension">.ubirimi.net</span>
                    <input id="domain" type="text" name="company_domain" value="<?php if (isset($_POST['company_domain'])) echo $_POST['company_domain'] ?>" />
                </div>
                <?php if ($errors['empty_company_domain']): ?>
                    <br />
                    <p class="error">The domain can not be empty</p>
                <?php elseif ($errors['company_domain_not_valid']): ?>
                    <br />
                    <p class="error">The domain should contain only small english letters.</p>
                <?php elseif ($errors['company_domain_not_unique']): ?>
                    <br />
                    <p class="error">The domain is not available.</p>
                <?php endif ?>
            </fieldset>
        </div>
    </div>

    <div class="form-section clearfix">
        <h3>2. Account details</h3>
        <div class="section-left align-left sectionFeature blue">
            <fieldset>
                <label for="cname">Company name</label>
                <input id="cname" type="text" name="company_name" value="<?php if (isset($_POST['company_name'])) echo $_POST['company_name'] ?>" />
                <?php if ($errors['empty_company_name']): ?>
                    <br />
                    <p class="error">The company name can not be empty</p>
                <?php endif ?>
            </fieldset>

            <fieldset>
                <label for="fname">First name</label>
                <input id="fname" type="text" name="admin_first_name" value="<?php if (isset($_POST['admin_first_name'])) echo $_POST['admin_first_name'] ?>" />
                <?php if ($errors['empty_admin_first_name']): ?>
                    <br />
                    <p class="error">The first name can not be empty</p>
                <?php endif ?>

            </fieldset>
            <fieldset>
                <label for="lname">Last name</label>
                <input id="lname" type="text" name="admin_last_name" value="<?php if (isset($_POST['admin_last_name'])) echo $_POST['admin_last_name'] ?>" name="admin_last_name" />
                <?php if ($errors['empty_admin_last_name']): ?>
                    <br />
                    <p class="error">The last name can not be empty</p>
                <?php endif ?>

            </fieldset>
            <fieldset>
                <label for="email">Email address</label>
                <input id="email" type="text" name="admin_email" value="<?php if (isset($_POST['admin_email'])) echo $_POST['admin_email'] ?>" />
                <?php if ($errors['empty_admin_email']): ?>
                    <br />
                    <p class="error">The email address can not be empty</p>
                <?php elseif ($errors['admin_email_not_valid']): ?>
                    <br />
                    <p class="error">The email address you provided is not valid.</p>
                <?php elseif ($errors['admin_email_already_exists']): ?>
                    <br />
                    <p class="error">The email address you provided is already in use.</p>
                <?php endif ?>
            </fieldset>
            <fieldset>
                <label for="username">Username</label>
                <input id="username" type="text" name="admin_username" value="<?php if (isset($_POST['admin_username'])) echo $_POST['admin_username'] ?>" />
                <?php if ($errors['empty_admin_username']): ?>
                    <br />
                    <p class="error-msg">The administrator username can not be empty</p>
                <?php elseif ($errors['invalid_username']): ?>
                    <p class="error">The username is not valid.</p>
                <?php endif ?>

            </fieldset>
            <fieldset>
                <label for="password">Password</label>
                <input id="password" type="password" name="admin_pass_1" value="<?php if (isset($_POST['admin_pass_1'])) echo $_POST['admin_pass_1'] ?>" />
                <?php if ($errors['empty_admin_pass_1']): ?>
                    <br />
                    <p class="error">The password can not be empty</p>
                <?php elseif ($errors['passwords_do_not_match']): ?>
                    <br />
                    <p class="error">Passwords do not match</p>
                <?php endif ?>

            </fieldset>
            <fieldset>
                <label for="cpassword">Confirm password</label>
                <input id="cpassword" type="password" name="admin_pass_2" value="<?php if (isset($_POST['admin_pass_2'])) echo $_POST['admin_pass_2'] ?>" />
            </fieldset>
            <fieldset>
                <label class="checkbox-container">
                    <label>
                        <span class="checkbox"></span>
                        <input id="terms" type="checkbox" class="hidden" name="agree_terms" <?php if (isset($_POST['agree_terms'])) echo 'checked="checked"' ?> />
                        I have read and accept the <a href="/terms-of-service" target="_blank" class="link">terms of use</a> agreement
                        <?php if ($errors['not_agree_terms']): ?>
                            <br />
                            <p class="error">You must accept the terms and conditions.</p>
                        <?php endif ?>
                    </label>
                </label>
            </fieldset>

        </div>
    </div>

    <button type="submit" class="button_hp blue" name="add_company">Create Account</button>
</form>