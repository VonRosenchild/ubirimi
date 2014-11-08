<?php
use Ubirimi\Util;

require_once __DIR__ . '/_header.php';
?>
<body>
    <?php require_once __DIR__ . '/_menu.php'; ?>
    <?php if (Util::userHasClientAdministrationPermission()): ?>
        <?php Util::renderBreadCrumb('GeneralSettings Settings') ?>
    <?php endif ?>

    <div class="pageContent">
        <?php if (Util::userHasClientAdministrationPermission()): ?>
            <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
                <tr>
                    <td><a href="/general-settings/edit-general" class="btn ubirimi-btn"><i class="icon-edit"></i> Edit</a></td>
                </tr>
            </table>

            <div class="separationVertical"></div>
            <div class="headerPageText">Settings</div>
            <table class="table table-hover table-condensed">
                <tr>
                    <td width="30%">Title</td>
                    <td><?php echo $clientSettings['title_name'] ?></td>
                </tr>
                <tr>
                    <td width="30%">Mode</td>
                    <td><?php if ($clientSettings['operating_mode'] == 'public') echo 'Public'; else echo 'Private' ?></td>
                </tr>
                <tr>
                    <td width="30%">Base URL</td>
                    <td>https://<?php echo $client['company_domain'] ?>.ubirimi.net</td>
                </tr>
            </table>
            <br />
            <div class="headerPageText">Internationalization</div>

            <table class="table table-hover table-condensed">
                <tbody>
                    <tr>
                        <td width="30%">Default Language</td>
                        <td><?php echo ucfirst($clientSettings['language']) ?></td>
                    </tr>

                    <tr>
                        <td width="30%">Default user time zone</td>
                        <td><?php echo str_replace("_", " ", $clientSettings['timezone']) ?></td>
                    </tr>
                </tbody>
            </table>
        <?php else: ?>
            <?php Util::renderContactSystemAdministrator() ?>
        <?php endif ?>
    </div>
    <?php require_once __DIR__ . '/_footer.php' ?>
</body>