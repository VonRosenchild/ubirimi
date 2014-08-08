<?php $isDemo = in_array($httpHOST, array('https://demo.ubirimi.net', 'https://demo.ubirimi_net.lan')) ?>
<div class="container page-container login">
    <table align="center">
        <tr>
            <td>
                <p class="leading" style="text-align: center;">
                    <?php echo $_SERVER['HTTP_HOST'] ?>
                </p>
            </td>
        </tr>
    </table>
    <table align="center">
        <tr>
            <td>
                <div>
                    <div class="align-left">
                        <form name="f_login" method="post" class="standard-form" action="/<?php if (isset($context)) echo '?context=' . $context ?>">
                            <?php if ($isDemo): ?>
                            <p>Log in with demo/demo</p>
                            <?php endif ?>
                            <div class="form-section clearfix sectionFeature blue">
                                <fieldset>
                                    <label for="sign-in-username">username</label>
                                    <input tabindex="1" id="sign-in-username" type="text" value="<?php if ($isDemo): ?>demo<?php endif ?>" name="username" style="width:240px; "/>
                                </fieldset>

                                <fieldset>
                                    <label for="sign-in-password">password</label>
                                    <input tabindex="2  " id="sign-in-password" type="password" value="<?php if ($isDemo): ?>demo<?php endif ?>" name="password" style="width:240px;"/>
                                </fieldset>

                                <?php if ($signInError): ?>
                                    <span class="error">Username and password do not match.</span>
                                <?php endif ?>

                                <?php if (!$isDemo): ?>
                                    <fieldset>
                                        <a class="link" href="/recover-password">Forgot your password?</a>
                                    </fieldset>
                                <?php endif ?>
                            </div>

                            <button type="submit" class="button_hp blue" name="sign_in">Login</button>
                            <?php if ('public' == $clientSettings['operating_mode']): ?>
                                <fieldset>
                                    <label>Not already a member?</label>
                                </fieldset>
                                <button type="submit" class="button_hp blue" name="create_account">Create User Account</button>
                            <?php endif ?>
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
        <img src="/img/site/bg.home.logos.h.png" width="49px" style="padding-right: 8px" />
        <img src="/img/site/bg.home.logos.d.png" width="49px" style="padding-right: 8px" />
        <img src="/img/site/bg.home.logos.s.png" width="49px" style="padding-right: 8px" />
        <img src="/img/site/bg.home.logos.e.png" width="49px" style="padding-right: 8px" />
    </div>
</div>

