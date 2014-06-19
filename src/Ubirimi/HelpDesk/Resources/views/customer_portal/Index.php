<?php
    require_once __DIR__ . '/../../../../Yongo/Resources/views/_header.php';
?>

<body style="background-color: #ffffff">
    <div class="container page-container login">

        <div align="center">
            <img src="/img/site/bg.logo.png" style="margin-bottom: 18px;" />
        </div>

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
                            <form name="f_login" method="post" class="standard-form" action="/helpdesk/customer-portal/sign-in">
                                <div class="form-section clearfix sectionFeature blue">
                                    <fieldset>
                                        <label for="sign-in-username">email address</label>
                                        <input id="sign-in-username" type="text" value="" name="username"
                                               style="width:240px; "/>
                                    </fieldset>

                                    <fieldset>
                                        <label for="sign-in-password">password</label>
                                        <input id="sign-in-password" type="password" value="" name="password"
                                               style="width:240px;"/>
                                    </fieldset>

                                    <?php if ($signInError): ?>
                                        <span class="error">email address and password do not match.</span>
                                    <?php endif ?>

                                    <fieldset>
                                        <a class="link" href="/recover-password">Forgot your password?</a>
                                    </fieldset>
                                </div>

                                <button type="submit" class="button_hp blue" name="sign_in">Login</button>

                                <fieldset>
                                    <label>Not already a customer?</label>
                                </fieldset>
                                <button type="submit" class="button_hp blue" name="create_account">Create Customer Account</button>
                            </form>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
        <br />
        <hr size="1" />
        <br />
        <div align="center">
            <img src="/img/site/bg.home.logos.y.png" width="49px" style="padding-right: 8px" />
            <img src="/img/site/bg.home.logos.a.png" width="49px" style="padding-right: 8px" />
            <img src="/img/site/bg.home.logos.d.png" width="49px" style="padding-right: 8px" />
            <img src="/img/site/bg.home.logos.s.png" width="49px" style="padding-right: 8px" />
            <img src="/img/site/bg.home.logos.e.png" width="49px" style="padding-right: 8px" />
        </div>
    </div>

</body>