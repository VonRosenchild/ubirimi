<?php
    use Ubirimi\Util;

    require_once __DIR__ . '/../../_header.php';
?>
<body>
    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <?php if (Util::userHasDocumentatorAdministrativePermission()): ?>
        <?php Util::renderBreadCrumb('Groups') ?>
    <?php endif ?>
    <div class="pageContent">
        <?php if (Util::userHasDocumentatorAdministrativePermission()): ?>

            <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
                <tr>
                    <td><a id="btnNewGroupDocumentator" href="/documentador/administration/groups/add" class="btn ubirimi-btn"><i class="icon-plus"></i> Create Group</a></td>
                    <td><a id="btnEditGroupDocumentator" href="#" class="btn ubirimi-btn disabled"><i class="icon-edit"></i> Edit</a></td>
                    <td><a id="btnDeleteGroupDocumentator" href="#" class="btn ubirimi-btn disabled"><i class="icon-remove"></i> Delete</a></td>
                    <td><a id="btnAssignUserInGroupDocumentator" href="#" class="btn ubirimi-btn disabled">Assign users</a></td>
                </tr>
            </table>

            <?php if ($groups): ?>
                <table class="table table-hover table-condensed">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Name</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($group = $groups->fetch_array(MYSQLI_ASSOC)): ?>
                            <tr id="table_row_<?php echo $group['id'] ?>">
                                <td width="22">
                                    <input type="checkbox" value="1" id="el_check_<?php echo $group['id'] ?>" />
                                </td>
                                <td><?php echo $group['name'] ?></td>
                                <td><?php echo $group['description'] ?></td>
                            </tr>
                        <?php endwhile ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="messageGreen">There are no groups defined.</div>
            <?php endif ?>
            <div id="assignUsersInGroup"></div>
            <div id="deleteUserGroup"></div>
        <?php else: ?>
            <?php Util::renderContactSystemAdministrator() ?>
        <?php endif ?>
    </div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>