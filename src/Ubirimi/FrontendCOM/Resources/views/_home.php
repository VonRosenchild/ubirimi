<!DOCTYPE html>
    <!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
    <!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
    <!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
    <!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <?php
        use Ubirimi\Util;

        require_once __DIR__ . '/_header.php'
    ?>

    <body>
        <header class="inverse" itemscope itemtype="http://schema.org/WPHeader" style="background-color: background-color: rgba(44, 185, 232, 0.46);">
            <div class="container clearfix" style="width: 1000px;">
                <nav class="buttons_hp align-right">
                    <?php if (Util::runsOnLocalhost()): ?>
                        <a class="button_hp gray" href="http://demo.ubirimi_net.lan/">Fast try</a>
                    <?php else: ?>
                        <a class="button_hp gray" href="https://demo.ubirimi.net/">Fast try</a>
                    <?php endif ?>
                    <a class="button_hp blue" href="/sign-up">Sign up</a>
                </nav>

                <nav class="menu align-right">
                    <?php if (Util::runsOnLocalhost()): ?>
                    <a itemprop="url" href="/products">Products</a>
                    <a itemprop="url" href="/pricing">Pricing</a>
                    <a itemprop="url" href="/company">Company</a>
                    <a itemprop="url" href="/blog">Blog</a>
                    <?php else: ?>
                    <a itemprop="url" href="https://www.ubirimi.com/products">Products</a>
                    <a itemprop="url" href="https://www.ubirimi.com/pricing">Pricing</a>
                    <a itemprop="url" href="https://www.ubirimi.com/company">Company</a>
                    <a itemprop="url" href="https://www.ubirimi.com/blog">Blog</a>
                    <?php endif ?>

                    <span class="separator"></span>
                    <?php if (Util::checkUserIsLoggedIn()): ?>
                        <?php if (Util::runsOnLocalhost()): ?>
                            <a itemprop="url" <?php if ('login' == $page): ?>class="selected"<?php endif ?> href="http://my.ubirimi.lan/account/home"><?php echo $session->get('user/first_name') . ' ' . $session->get('user/last_name') ?></a>
                            <a itemprop="url" href="http://my.ubirimi.lan/sign-out">Sign Out</a>
                        <?php else: ?>
                            <a itemprop="url" <?php if ('login' == $page): ?>class="selected"<?php endif ?> href="https://my.ubirimi.com/account/home"><?php echo $session->get('user/first_name') . ' ' . $session->get('user/last_name') ?></a>
                            <a itemprop="url" href="https://my.ubirimi.com/sign-out">Sign Out</a>
                        <?php endif ?>
                    <?php else: ?>
                        <?php if (Util::runsOnLocalhost()): ?>
                            <a itemprop="url" <?php if ('login' == $page): ?>class="selected"<?php endif ?> href="http://my.ubirimi.lan/sign-in">My Account</a>
                        <?php else: ?>
                            <a itemprop="url" <?php if ('login' == $page): ?>class="selected"<?php endif ?> href="https://my.ubirimi.com/sign-in">My Account</a>
                        <?php endif ?>
                    <?php endif ?>

                </nav>

                <?php if (Util::runsOnLocalhost()): ?>
                    <a itemprop="url" href="http://ubirimi.lan" class="logo">ubirimi</a>
                <?php else: ?>
                    <a itemprop="url" href="https://www.ubirimi.com" class="logo"ubirimi>ubirimi</a>
                <?php endif ?>
            </div>
            <div style="width: 100%; height: 2px; background: #ffffff url('/img/site/bg.page.png') 0 0 repeat-x scroll;"></div>
            <div style="width: 100%; height: 2px; background: #ffffff url('/img/site/bg.page.png') 0 0 repeat-x scroll;"></div>

        </header>

        <?php if ($page == 'index'): ?>
            <div style="width: 100%; margin-top: 4px; height: 500px; background-color: rgba(44, 185, 232, 0.46); vertical-align: middle" align="center">
                <div class="splash">Fast, most affordable productivity tools</div>
                <div class="splash_small">Plan, work together, organize, stay in touch</div>
            </div>
        <?php endif ?>


        <div class="gray-bg">
            <div class="container page-container" style="width: 1420px">
                <?php require_once __DIR__ . '/' . $content ?>
            </div>
        </div>

        <?php require_once __DIR__ . '/_footer.php' ?>
    </body>
</html>
