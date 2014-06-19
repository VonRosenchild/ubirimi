<?php
    use Ubirimi\Util;
?>

<div class="submenu">
    <a href="home" <?php if ('account_home' == $page): ?>class="selected"<?php endif ?>>Home</a>
    <a href="profile" <?php if ('account_profile' == $page): ?>class="selected"<?php endif ?>>Profile</a>

    <?php if (Util::runsOnLocalhost()): ?>
        <a href="https://support.ubirimi_net.lan">Support</a>
    <?php else: ?>
        <a href="https://support.ubirimi.net/yongo/project/all">Support</a>
    <?php endif ?>

    <a href="/account/pay" <?php if ('account_pay' == $page): ?>class="selected"<?php endif ?>>Pay</a>
    <a href="/account/invoices" <?php if ('account_invoice' == $page): ?>class="selected"<?php endif ?>>Invoices</a>

    <?php if ($session->get('user/super_user_flag')): ?>
        <a href="/administration">Administration</a>
    <?php endif ?>
</div>
