<?php
use Ubirimi\Util;

require_once __DIR__ . '/../../../../Yongo/Resources/views/_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../../Resources/views/administration/_menu.php'; ?>
    <?php Util::renderBreadCrumb('Administration') ?>

    <div class="pageContent">

        <table width="100%">
            <tr>
                <td>
                    <a href="/helpdesk/administration/organizations">Organizations</a>
                    <a href="/helpdesk/administration/customers">Customers</a>
                </td>
            </tr>
        </table>
    </div>
    <?php require_once __DIR__ . '/../../../Resources/views/administration/_footer.php' ?>
</body>