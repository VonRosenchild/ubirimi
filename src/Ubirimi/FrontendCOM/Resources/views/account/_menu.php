<ul class="nav nav-tabs" style="padding: 0px;">
    <li <?php if ($page == 'account_home'): ?>class="active"<?php endif ?>>
        <a href="/account/home" title="Summary">Summary</a>
    </li>
    <li <?php if ($page == 'account_profile'): ?>class="active" <?php endif ?>>
        <a href="/account/profile" title="Issues">Profile</a>
    </li>
    <li <?php if ($page == 'account_invoice'): ?>class="active" <?php endif ?>>
        <a href="/account/invoices" title="Invoices">Invoices</a>
    </li>
    <li <?php if ($page == 'account_billing' || $page == 'account_billing_update'): ?>class="active" <?php endif ?>>
        <a href="/account/billing" title="Billing">Billing</a>
    </li>
    <li <?php if ($page == 'account_settings'): ?>class="active" <?php endif ?>>
        <a href="/account/settings" title="Settings">Settings</a>
    </li>
    <?php if ($session->get('user/super_user_flag')): ?>
        <li>
            <a href="/administration" title="Administration">Administration</a>
        </li>
    <?php endif ?>
</ul>