<?php
use Ubirimi\Util;

require_once __DIR__ . '/../_header.php';
    $administrationView = true;
?>
<body>

    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <?php if ($accessToPage): ?>
        <?php Util::renderBreadCrumb('Projects') ?>
    <?php endif ?>
    <div class="pageContent">
        <?php if ($accessToPage): ?>
            <?php if ($hasGlobalAdministrationPermission || $hasGlobalSystemAdministrationPermission): ?>
                <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
                    <tr>
                        <td><a id="btnNew" href="/yongo/administration/project/add" class="btn ubirimi-btn"><i class="icon-plus"></i> Create New Project</a></td>
                        <td><a id="btnEditClientProject" href="/yongo/administration/workflows/edit-scheme/" class="btn ubirimi-btn disabled"><i class="icon-edit"></i> Edit</a></td>
                        <td><a id="btnDeleteClientProject" href="#" class="btn ubirimi-btn disabled"><i class="icon-remove"></i> Delete</a></td>
                    </tr>
                </table>
            <?php endif ?>

            <?php if ($projects): ?>
                <?php require_once __DIR__ . '/_projectInCategory.php' ?>

                <div id="deleteClientProject"></div>
                <div id="assignUsers"></div>
            <?php else: ?>
                <div class="messageGreen">There are no projects created.</div>
            <?php endif ?>
        <?php else: ?>
            <div class="infoBox">Unauthorized access. Please contact the system administrator.</div>
        <?php endif ?>
    </div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>