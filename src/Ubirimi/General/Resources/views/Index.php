<?php
use Ubirimi\Util;

require_once __DIR__ . '/_header.php';
?>
<body>
    <?php require_once __DIR__ . '/_menu.php'; ?>

    <?php Util::renderBreadCrumb('General Settings') ?>

    <div class="pageContent">
        <table width="100%">
            <?php if (Util::userHasClientAdministrationPermission()): ?>
                <tr>
                    <td class="sectionDetail"><span class="sectionDetailSimple headerPageText">Overview</span></td>
                </tr>
                <tr>
                    <td>
                        <a href="/general-settings/view-general">General Settings</a>
                        <b>&middot;</b>
                        <a href="/general-settings/logs/<?php echo $lastWeekToday ?>/<?php echo $today ?>">Logs</a>
                    </td>
                </tr>
                <tr>
                    <td class="sectionDetail"><span class="sectionDetailSimple headerPageText">Mail</span></td>
                </tr>
                <tr>
                    <td>

                        <a href="/general-settings/smtp-settings">SMTP Server Settings</a>
                        <b>&middot;</b>
                        <a href="/general-settings/main-queue">Mail Queue</a>
                    </td>
                </tr>
                <tr>
                    <td class="sectionDetail"><span class="sectionDetailSimple headerPageText">Applications</span></td>
                </tr>
                <tr>
                    <td>
                        <a href="/general-settings/applications/manage">Manage</a>
                    </td>
                </tr>
            <?php endif ?>
            <tr>
                <td class="sectionDetail"><span class="sectionDetailSimple headerPageText">Users</span></td>
            </tr>
            <tr>
                <td>
                    <a href="/general-settings/users">Users</a>
                </td>
            </tr>
        </table>
    </div>
    <?php require_once __DIR__ . '/_footer.php' ?>
</body>