<?php
    use Ubirimi\Repository\Group\Group;
    use Ubirimi\Repository\User\User;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Permission\PermissionScheme;

    require_once __DIR__ . '/../_header.php';
?>
<body>
    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <div class="pageContent">
        <?php if (Util::userHasYongoAdministrativePermission()): ?>
            <?php Util::renderBreadCrumb('Groups') ?>

            <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
                <tr>
                    <td><a id="btnNew" href="/yongo/administration/group/add" class="btn ubirimi-btn"><i class="icon-plus"></i> Create Group</a></td>
                    <td><a id="btnEditUserGroup" href="#" class="btn ubirimi-btn disabled"><i class="icon-edit"></i> Edit</a></td>
                    <td><a id="btnDeleteUserGroup" href="#" class="btn ubirimi-btn disabled"><i class="icon-remove"></i> Delete</a></td>
                    <td><a id="btnAssignUserInGroup" href="#" class="btn ubirimi-btn disabled">Assign users</a></td>
                </tr>
            </table>
            <table cellspacing="0" border="0" cellpadding="0" width="100%">
                <tr>
                    <td width="260px">Name contains</td>
                </tr>
                <tr>
                    <td><input class="inputText" type="text" id="group_name_filter" /></td>
                </tr>
            </table>
            <div id="contentListGroups">
                <?php require_once __DIR__ . '/_list_group.php'; ?>
            </div>
            <div id="assignUsersInGroup"></div>
            <div id="deleteUserGroup"></div>
        <?php else: ?>
            <?php Util::renderContactSystemAdministrator() ?>
        <?php endif ?>
    </div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>