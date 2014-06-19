<?php
    use Ubirimi\Container\UbirimiContainer;
?>
<!DOCTYPE html>
<!--[if lt IE 7]>
<html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>
<html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>
<html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js"> <!--<![endif]-->
<?php
    use Ubirimi\Util;

    require_once __DIR__ . '/_header.php'
?>

<body>
    <header class="inverse" itemscope itemtype="http://schema.org/WPHeader">
        <div class="container clearfix" style="width: 1000px;">
            <nav class="buttons_hp align-right">

                <?php if (Util::runsOnLocalhost()): ?>
                    <a itemprop="url" class="button_hp gray" href="http://demo.ubirimi_net.lan">Fast try</a>
                    <a itemprop="url" class="button_hp blue" href="http://ubirimi.lan/sign-up">Sign up</a>
                <?php else: ?>
                    <a itemprop="url" class="button_hp gray" href="https://demo.ubirimi.net">Fast try</a>
                    <a itemprop="url" class="button_hp blue" href="https://www.ubirimi.com/sign-up">Sign up</a>
                <?php endif ?>

            </nav>
            <nav class="menu align-right">
                <?php if (Util::runsOnLocalhost()): ?>
                <a itemprop="url" <?php if ('products' == $page): ?>class="selected"<?php endif ?> href="/products">Products</a>
                <a itemprop="url" <?php if ('pricing' == $page): ?>class="selected"<?php endif ?> href="/pricing">Pricing</a>
                <a itemprop="url" <?php if ('company' == $page): ?>class="selected"<?php endif ?> href="/company">Company</a>
                <a itemprop="url" <?php if ('blog' == $page): ?>class="selected"<?php endif ?> href="/blog">Blog</a>
                <?php else: ?>
                <a itemprop="url" <?php if ('products' == $page): ?>class="selected"<?php endif ?> href="https://www.ubirimi.com/products">Products</a>
                <a itemprop="url" <?php if ('pricing' == $page): ?>class="selected"<?php endif ?> href="https://www.ubirimi.com/pricing">Pricing</a>
                <a itemprop="url" <?php if ('company' == $page): ?>class="selected"<?php endif ?> href="https://www.ubirimi.com/company">Company</a>
                <a itemprop="url" <?php if ('blog' == $page): ?>class="selected"<?php endif ?> href="https://www.ubirimi.com/blog">Blog</a>
                <?php endif ?>

                <span class="separator"></span>
                <?php if (Util::checkUserIsLoggedIn()): ?>
                    <?php if (Util::runsOnLocalhost()): ?>
                        <a itemprop="url" <?php if ('account_home' == $page || 'account_profile' == $page): ?>class="selected"<?php endif ?> href="http://my.ubirimi.lan/account/home"><?php echo $session->get('user/first_name') . ' ' . $session->get('user/last_name') ?></a>
                        <a itemprop="url" href="http://my.ubirimi.lan/sign-out">Sign Out</a>
                    <?php else: ?>
                        <a itemprop="url" <?php if ('account_home' == $page || 'account_profile' == $page): ?>class="selected"<?php endif ?> href="https://my.ubirimi.com/account/home"><?php echo $session->get('user/first_name') . ' ' . $session->get('user/last_name') ?></a>
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
                <a itemprop="url" href="https://www.ubirimi.com" class="logo">ubirimi</a>
            <?php endif ?>
        </div>
        <div style="width: 100%; height: 2px; background: #ffffff url('/img/site/bg.page.png') 0 0 repeat-x scroll;"></div>
    </header>

    <table align="center" style="width: 1000px">
        <tr>
            <td>
                <?php require_once __DIR__ . '/' .  $content ?>
            </td>
        </tr>
    </table>
    <?php require_once __DIR__ . '/_footer.php' ?>

</body>
</html>