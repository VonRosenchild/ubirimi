<?php
    require_once __DIR__ . '/../../../../Yongo/Resources/views/_header.php';
?>

<body style="background-color: #ffffff">
    <div class="container page-container login">

        <table align="center">
            <tr>
                <td align="center">
                    <h1>Customer Portal</h1>

                    <p class="leading">
                        <?php echo $_SERVER['HTTP_HOST'] ?>
                    </p>
                </td>
            </tr>
        </table>

        <table align="center">
            <tr>
                <td>
                    <div class="clearfix">
                        <div class="align-left">
                            <form name="f_login" method="post" class="standard-form" action="/helpdesk/customer-portal/sign-up">
                                <div class="form-section clearfix sectionFeature blue">
                                    <fieldset>
                                        <label for="sign-in-username">first name <span class="mandatory">*</span></label>
                                        <input id="sign-in-username" type="text" value="" name="first_name"
                                               style="width:240px; "/>
                                        <?php if ($errors['empty_first_name']): ?>
                                            <div class="HPFieldLabel14Red">The first name can not be empty.</div>
                                        <?php endif ?>
                                    </fieldset>
                                    <fieldset>
                                        <label for="sign-in-username">last name <span class="mandatory">*</span></label>
                                        <input id="sign-in-username" type="text" value="" name="last_name"
                                               style="width:240px; "/>
                                        <?php if ($errors['empty_last_name']): ?>
                                            <div class="HPFieldLabel14Red">The last name can not be empty.</div>
                                        <?php endif ?>
                                    </fieldset>

                                    <fieldset>
                                        <label for="sign-in-username">email address <span class="mandatory">*</span></label>
                                        <input id="sign-in-username" type="text" value="" name="email_address"
                                               style="width:240px; "/>
                                        <?php if ($errors['empty_email']): ?>
                                            <div class="HPFieldLabel14Red">The email address can not be empty.</div>
                                        <?php elseif ($errors['email_not_valid']): ?>
                                            <div class="HPFieldLabel14Red">The email address is not valid.</div>
                                        <?php elseif ($errors['email_already_exists']): ?>
                                            <div class="HPFieldLabel14Red">The email address is not available.</div>
                                        <?php endif ?>
                                    </fieldset>
                                    <fieldset>
                                        <label for="sign-in-password">password <span class="mandatory">*</span></label>
                                        <input id="sign-in-password" type="password" value="" name="password"
                                               style="width:240px;"/>
                                        <?php if ($errors['empty_password']): ?>
                                            <div class="HPFieldLabel14Red">The password can not be empty.</div>
                                        <?php endif ?>

                                    </fieldset>
                                    <fieldset>
                                        <label for="sign-in-password">repeat password <span class="mandatory">*</span></label>
                                        <input id="sign-in-password" type="password" value="" name="password_repeat"
                                               style="width:240px;"/>
                                        <?php if ($errors['password_mismatch']): ?>
                                            <div class="HPFieldLabel14Red">The passwords do not match.</div>
                                        <?php endif ?>
                                        <?php if ($errors['empty_email']): ?>
                                            <div class="HPFieldLabel14Red">The email address can not be empty.</div>
                                        <?php elseif ($errors['email_not_valid']): ?>
                                            <div class="HPFieldLabel14Red">The email address is not valid.</div>
                                        <?php elseif ($errors['email_already_exists']): ?>
                                            <div class="HPFieldLabel14Red">The email address is already assigned to another user.</div>
                                        <?php endif ?>

                                    </fieldset>
                                </div>

                                <button type="submit" class="button_hp blue" name="create_account">Create Customer Account</button>
                                <a class="link" href="/helpdesk/customer-portal">Cancel</a>
                            </form>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <br />
    <hr size="1" style="width: 70%; margin-bottom: 20px;" />
    <div align="center">
        <img src="/img/site/bg.logo.png" style="margin-bottom: 18px; opacity: 0.5" />
    </div>
</body>