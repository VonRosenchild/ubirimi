<?php
use Ubirimi\Util;

require_once __DIR__ . '/../../_header.php';
?>
<body>
    <?php require_once __DIR__ . '/../../_menu.php'; ?>
    <?php Util::renderBreadCrumb('Issue Features > Time Tracking') ?>
    <div class="pageContent">

        <ul class="nav nav-tabs" style="padding: 0px;">
            <li class="active"><a href="/yongo/administration/issue-features/time-tracking">Time Tracking</a></li>
            <li><a href="/yongo/administration/issue-features/linking">Issue Linking</a></li>
        </ul>

        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <?php if ($timeTrackingFlag): ?>
                    <td><a href="/yongo/administration/time-tracking/edit" class="btn ubirimi-btn"><i class="icon-wrench"></i> Configure</a></td>
                <?php endif ?>
                <td><a href="/yongo/administration/toggle-time-tracking" class="btn ubirimi-btn"><?php if ($timeTrackingFlag) echo 'Deactivate'; else echo 'Activate' ?></a></td>
            </tr>
        </table>

        <?php if ($timeTrackingFlag): ?>
            <div>The number of working hours per day is <?php echo $session->get('yongo/settings/time_tracking_hours_per_day'); ?>.</div>
            <div>The number of working days per week is <?php echo $session->get('yongo/settings/time_tracking_days_per_week') ?>.</div>
            <div>The current default unit for time tracking is <b><?php echo $defaultTimeTracking ?></b>.</div>
        <?php else: ?>
            <div class="infoBox">Time tracking is currently deactivated.</div>
        <?php endif ?>
    </div>

    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>