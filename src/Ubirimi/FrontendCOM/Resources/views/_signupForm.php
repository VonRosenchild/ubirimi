<form class="standard-form horizontal" method="post" name="client-sign-up" autocomplete="off" id="signUp-form" action="/sign-up">
    <?php if ($session->has('client_account_created')): ?>
    <div class="global-msg confirmation">
        Done. Check your email in a few minutes for further details.
    </div>
    <?php endif ?>
    <table align="center">
        <tr>
            <td colspan="3">
                <h1>Create your account</h1>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <a class="button_hp blue" href="#">Free for first month</a>
                <a class="button_hp blue" href="#">Cancel at any time</a>
                <a class="button_hp blue" href="#">Free updates forever</a>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <div>&nbsp;</div>
            </td>
        </tr>
        <tr>
            <td valign="top">
                <div class="form-section clearfix">
                    <h3>1. On Demand URL</h3>
                    <div class="section-left align-left sectionFeature blue">
                        <fieldset>
                            <label for="domain">Company domain</label>

                                <input style="width: 100px" id="domain" type="text" name="company_domain" value="<?php if (isset($_POST['company_domain'])) echo $_POST['company_domain'] ?>" />
                                <label style="display: block; width: 10px;"> </label>
                                <label style="background-color: #e8e8e8; border: 1px solid #d9d9d9; padding: 3px;">.ubirimi.net</label>
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
                            <?php elseif ($errors['admin_email_already_exists']): ?>
                                <p class="error">The username is not available.</p>
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
                            <label for="country">Country</label>
                            <select id="country" name="country" style="width: 296px">
                                <?php while ($country = $countries->fetch_array(MYSQLI_ASSOC)): ?>
                                    <option value="<?php echo $country['id'] ?>"><?php echo $country['name'] ?></option>
                                <?php endwhile ?>
                            </select>
                        </fieldset>
                        <fieldset style="display: none" id="vat_container">
                            <label for="country">VAT Number</label>
                            <input id="vat_number" type="text" name="var_number" value="<?php if (isset($_POST['vat_number'])) echo $_POST['vat_number'] ?>" />
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
            </td>
            <td width="50px"></td>
            <td valign="top">
                <div class="form-section clearfix">
                    <h3>3. Payment details</h3>
                    <div class="section-left align-left sectionFeature blue">
                        <fieldset>
                            <label for="card_number">Card number</label>
                            <div class="field-extension-container">
                                <input id="card_number" type="text" name="card_number" value="<?php if (isset($_POST['card_number'])) echo $_POST['card_number'] ?>" />
                            </div>
                            <?php if ($errors['empty_card_number']): ?>
                                <br />
                                <p class="error">The card number can not be empty</p>
                            <?php endif ?>
                        </fieldset>
                        <fieldset>
                            <label for="fname">Expiration date</label>
                            <input style="width: 50px" id="card_exp_month" type="text" name="card_exp_month" value="<?php if (isset($_POST['card_exp_month'])) echo $_POST['card_exp_month'] ?>" />
                            <span style="display: block; float: left">&nbsp;&nbsp;</span>
                            <input style="width: 50px" id="card_exp_year" type="text" name="card_exp_year" value="<?php if (isset($_POST['card_exp_year'])) echo $_POST['card_exp_year'] ?>" />
                            <?php if ($errors['card_exp_month'] || $errors['card_exp_year']): ?>
                                <br />
                                <p class="error">The expiration is wrong.</p>
                            <?php endif ?>
                        </fieldset>
                        <fieldset>
                            <label for="card_name">Name on card</label>
                            <input id="card_name" type="text" name="card_name" value="<?php if (isset($_POST['card_name'])) echo $_POST['card_name'] ?>" />
                            <?php if ($errors['empty_card_name']): ?>
                                <br />
                                <p class="error">The card name can not be empty</p>
                            <?php endif ?>
                        </fieldset>

                        <fieldset>
                            <label for="fname">Security code</label>
                            <input style="width: 100px" id="card_security" type="text" name="card_security" value="<?php if (isset($_POST['card_security'])) echo $_POST['card_security'] ?>" />
                            <?php if ($errors['empty_card_security']): ?>
                                <br />
                                <p class="error">The security code can not be empty</p>
                            <?php endif ?>
                        </fieldset>
                        <div class="payment_errors error"></div>
                    </div>

                </div>

                <div class="form-section clearfix">
                    <h3>4. Number of users</h3>

                    <div class="section-left align-left sectionFeature blue">
                        <fieldset>
                            <label for="number_users">Number of users</label>

                            <select name="number_users" id="number_users" style="width: 100px">
                                <option value="10">10</option>
                                <option value="15">15</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="500">500</option>
                                <option value="1000">1000</option>
                            </select>
                        </fieldset>
                        <fieldset>
                            <label>Amount to pay</label>
                            <input id="pay_amount" style="width: 100px" type="text" disabled="disabled" value="10" /> <label style="display: block; width: 10px"> </label> <label>$ / month</label>
                        </fieldset>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <button type="submit" class="button_hp blue" name="add_company">Create Account</button>
            </td>
        </tr>
    </table>
</form>