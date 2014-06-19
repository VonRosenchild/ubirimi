<?php
    use Ubirimi\Repository\Client;
    use Ubirimi\Util;

    require_once __DIR__ . '/../include/header.php';

    Util::checkUserIsLoggedInAndRedirect();

    $settings = Client::getYongoSettings($clientId);
    $menuSelectedCategory = 'system';

    $file = false;
    if ($handle = opendir('./../backup')) {
        while (false !== ($entry = readdir($handle))) {
            if (preg_match('/^ubirimi_backup_[0-9]{4}-[0-9]{2}-[0-9]{2}\.zip$/', $entry, $matches)) {
                $file = $matches[0];
            }
        }
        closedir($handle);
    }
?>

<body>
    <?php require_once __DIR__ . '/../include/menu.php'; ?>
    <div class="pageContent">
        <table width="100%" class="headerPageBackground">
            <tr>
                <td>
                    <div class="headerPageText">Backup Manager</div>
                </td>
            </tr>
        </table>
        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <td><a id="btnStartBackup" href="#" class="btn ubirimi-btn"><i class="icon-plus"></i> Create Backup</a></td>
            </tr>
        </table>

        <div class="infoBox">You can back up your current application data in Ubirimi. A new backup overwrites the previous backup file</div>
        <div id="current_backup">
            <?php if ($file): ?>
                <div>Your current backup file is: </div>
                <a href="./../backup/<?php echo $file ?>"><?php echo $file ?></a>
            <?php else: ?>
                <span>Currently, you do not have any backup file.</span>
            <?php endif ?>
        </div>
    </div>
    <?php require_once __DIR__ . '/../include/footer.php' ?>
</body>