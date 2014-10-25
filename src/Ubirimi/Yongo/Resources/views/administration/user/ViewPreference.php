<?php
use Ubirimi\Util;

require_once __DIR__ . '/../_header.php';
?>
<body>
    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <?php if (Util::userHasYongoAdministrativePermission()): ?>
        <?php Util::renderBreadCrumb('User Preferences') ?>
    <?php endif ?>
    <div class="pageContent">
        <?php if (Util::userHasYongoAdministrativePermission()): ?>
            <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
                <tr>
                    <td><a href="/yongo/administration/user-preference/edit" class="btn ubirimi-btn"><i class="icon-edit"></i> Edit Default Values</a></td>
                </tr>
            </table>

            <div class="infoBox">Set the default values for user preferences. If the user has not specified a preference then the values for the user will fall back to the default values set here.</div>

            <table class="table table-hover table-condensed">
                <thead>
                    <tr>
                        <th width="400" align="left">Name</th>
                        <th>Value</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Number of issues displayed per Issue navigator page</td>
                        <td><?php echo $settings['issues_per_page'] ?></td>
                    </tr>
                    <tr>
                        <td>Notify users of their own changes?</td>
                        <td><?php if ($settings['notify_own_changes_flag']) echo 'YES'; else echo 'NO'; ?></td>
                    </tr>
                </tbody>
            </table>
        <?php else: ?>
            <?php Util::renderContactSystemAdministrator() ?>
        <?php endif?>
    </div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>