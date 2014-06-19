<div class="container page-container user-sing-up">
    <?php if ($client): ?>

        <h1>Create User Account</h1>
        <p class="leading"><?php echo $_SERVER['HTTP_HOST'] ?></p>

        <div class="">
            <div class="align-left">
                <form name="user-sign-up" method="post" action="/sign-up" class="standard-form" autocomplete="off" style="width: 400px;">
                    <?php require_once __DIR__ . '/_sigupForm.php' ?>
                    <?php if ($clientSettings['operating_mode'] == 'public'): ?>
                        <button class="button_hp blue" type="submit" name="create-user-account">Create User Account</button>
                    <?php endif ?>
                    <button class="button_hp blue" type="submit" name="cancel">Back</button>
                    <br />
                    <br />
                    <br />
                </form>
            </div>

            <div class="login-right align-left" style="width: 450px; padding-top: 50px;">
                <div class="HPFieldLabel25Red">Become part of the team</div>
                <ul>
                    <li>Make use of all the products</li>
                    <li>Share knowledge with your team</li>
                    <li>Manage your tasks</li>
                    <li>Track your team progress</li>
                    <li>Manage your events</li>
                </ul>
                <div style="border-top: 1px solid #d9e7f3; height: 2px;"></div>
                <br/> <img src="/img/site/bg.home.logos.y.png" width="69px" style="padding-right: 8px"/> <img src="/img/site/bg.home.logos.a.png" width="69px" style="padding-right: 8px"/>
                <img src="/img/site/bg.home.logos.d.png" width="69px" style="padding-right: 8px"/> <img src="/img/site/bg.home.logos.s.png" width="69px" style="padding-right: 8px"/>
                <img src="/img/site/bg.home.logos.e.png" width="69px" style="padding-right: 8px"/>
            </div>
        </div>
    <?php else: ?>
        <div class="HPFieldLabel14Red">Error</div>
    <?php endif ?>
</div>