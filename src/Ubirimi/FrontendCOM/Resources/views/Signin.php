<div class="container page-container login">
    <h1>My Account</h1>
    <div class="clearfix login-left align-left">
        <form class="standard-form" name="login" method="post" action="/sign-in">
            <div class="form-section clearfix sectionFeature blue">
                <fieldset>
                    <label for="email">Your username</label>
                    <input id="email" type="text" name="username">
                </fieldset>
                <fieldset>
                    <label for="name">Your password</label>
                    <input id="name" type="password" name="password">
                </fieldset>
                <?php if ($signInError): ?>
                    <span class="HPFieldLabel14 error" style="color: red;">Username and password do not match.</span>
                <?php endif ?>
                <fieldset>
<!--                    <label class="checkbox-container">-->
<!--                        <label>-->
<!--                            <span class="checkbox"></span>-->
<!--                            <input id="terms" type="checkbox" class="hidden">-->
<!--                        </label>-->
<!--                        <label class="checkbox-label" for="terms">-->
<!--                            Remember me-->
<!--                        </label>-->
<!--                        <br />-->
                        <a class="link" href="/recover-password">Forgot your password?</a>
                    </label>
                </fieldset>
            </div>
            <button type="submit" class="button_hp blue" name="sign_in">Login</button>
        </form>
    </div>

    <div class="clearfix login-right align-left">
        <div class="HPFieldLabel25Red">Here you can do the following:</div>
        <ul>
            <li>Access support system panel</li>
            <li>Change profile information</li>
            <li>Access your installation environment</li>
        </ul>
        <div style="border-top: 1px solid #d9e7f3; height: 2px;"></div>
        <br />
        <img src="/img/site/bg.home.logos.y.png" width="69px" style="padding-right: 8px" />
        <img src="/img/site/bg.home.logos.a.png" width="69px" style="padding-right: 8px" />
        <img src="/img/site/bg.home.logos.h.png" width="69px" style="padding-right: 8px" />
        <img src="/img/site/bg.home.logos.d.png" width="69px" style="padding-right: 8px" />
        <img src="/img/site/bg.home.logos.s.png" width="69px" style="padding-right: 8px" />
        <img src="/img/site/bg.home.logos.e.png" width="69px" style="padding-right: 8px" />
    </div>
    <div class="clearfix"></div>
</div>
<br />
<br />
<br />
<br />