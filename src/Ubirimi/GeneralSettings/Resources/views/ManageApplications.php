<?php
use Ubirimi\SystemProduct;
use Ubirimi\Util;

require_once __DIR__ . '/_header.php';
?>
<body>
    <?php require_once __DIR__ . '/_menu.php'; ?>
    <?php Util::renderBreadCrumb('GeneralSettings Settings > Manage Applications') ?>

    <div class="pageContent">
        <table width="100%">
            <?php if (Util::userHasClientAdministrationPermission()): ?>
                <tr>
                    <td colspan="2">Enable Applications</td>
                </tr>
                <tr>
                    <td width="20">
                        <input class="app_client" type="checkbox" value="1" name="yongo" <?php if (Util::checkKeyAndValueInArray('sys_product_id', SystemProduct::SYS_PRODUCT_YONGO, $productsArray)) echo 'checked="checked"' ?> />
                    </td>
                    <td>Yongo</td>
                </tr>
                <tr>
                    <td width="20">
                        <input class="app_client" type="checkbox" value="1" name="agile" <?php if (Util::checkKeyAndValueInArray('sys_product_id', SystemProduct::SYS_PRODUCT_AGILE, $productsArray)) echo 'checked="checked"' ?> />
                    </td>
                    <td>Agile</td>
                </tr>
                <tr>
                    <td width="20">
                        <input class="app_client" type="checkbox" value="1" name="helpdesk" <?php if (Util::checkKeyAndValueInArray('sys_product_id', SystemProduct::SYS_PRODUCT_HELP_DESK, $productsArray)) echo 'checked="checked"' ?> />
                    </td>
                    <td>Helpdesk</td>
                </tr>
                <tr>
                    <td width="20">
                        <input class="app_client" type="checkbox" value="1" name="svn" <?php if (Util::checkKeyAndValueInArray('sys_product_id', SystemProduct::SYS_PRODUCT_SVN_HOSTING, $productsArray)) echo 'checked="checked"' ?> />
                    </td>
                    <td>SVN Hosting</td>
                </tr>
                <tr>
                    <td width="20">
                        <input class="app_client" type="checkbox" value="1" name="documentador" <?php if (Util::checkKeyAndValueInArray('sys_product_id', SystemProduct::SYS_PRODUCT_DOCUMENTADOR, $productsArray)) echo 'checked="checked"' ?> />
                    </td>
                    <td>Documentador</td>
                </tr>
                <tr>
                    <td width="20">
                        <input class="app_client" type="checkbox" value="1" name="events" <?php if (Util::checkKeyAndValueInArray('sys_product_id', SystemProduct::SYS_PRODUCT_CALENDAR, $productsArray)) echo 'checked="checked"' ?> />
                    </td>
                    <td>Events</td>
                </tr>
            <?php endif ?>
        </table>
    </div>
    <?php require_once __DIR__ . '/_footer.php' ?>
</body>