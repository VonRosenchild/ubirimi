<?php
    use Ubirimi\Util;

    require_once __DIR__ . '/../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/_menu.php'; ?>

    <div class="pageContent">
        <?php if (Util::userHasDocumentatorAdministrativePermission() || $spacesWithAdminPermission): ?>
            <?php Util::renderBreadCrumb('Administration') ?>
            <?php if ($spacesWithAdminPermission || $hasDocumentatorGlobalAdministrationPermission || $hasDocumentatorGlobalSystemAdministrationPermission): ?>
                <table width="100%">
                    <tr>
                        <td colspan="2" class="sectionDetail"><span class="sectionDetailSimple headerPageText">Spaces</span></td>
                    </tr>
                    <tr>
                        <td>
                            <a href="/documentador/administration/spaces">Spaces</a>
                        </td>
                    </tr>
                </table>
            <?php endif ?>
            <?php if ($hasDocumentatorGlobalAdministrationPermission || $hasDocumentatorGlobalSystemAdministrationPermission): ?>
                <table width="100%">
                    <tr>
                        <td colspan="2" class="sectionDetail"><span class="sectionDetailSimple headerPageText">Users & Security</span></td>
                    </tr>
                    <tr>
                        <td>
                            <?php if ($hasDocumentatorGlobalAdministrationPermission || $hasDocumentatorGlobalSystemAdministrationPermission): ?>
                                <a href="/documentador/administration/users">Users</a>
                                <b>&middot;</b>
                                <a href="/documentador/administration/groups">Groups</a>
                            <?php endif ?>
                            <?php if ($hasDocumentatorGlobalSystemAdministrationPermission): ?>
                                <b>&middot;</b>
                                <a href="/documentador/administration/global-permissions">Global Permissions</a>
                            <?php endif ?>
                        </td>
                    </tr>
                </table>
            <?php endif ?>
        <?php else: ?>
            <?php Util::renderContactSystemAdministrator() ?>
        <?php endif ?>
    </div>
    <?php require_once __DIR__ . '/_footer.php' ?>
</body>