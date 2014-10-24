<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Permission\Role;

require_once __DIR__ . '/../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <div class="pageContent">

        <?php if (Util::userHasYongoAdministrativePermission()): ?>
            <?php Util::renderBreadCrumb('Roles') ?>

            <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
                <tr>
                    <td><a id="btnNewPermRole" href="/yongo/administration/role/add" class="btn ubirimi-btn"><i class="icon-plus"></i> Create Role</a></td>
                    <td><a id="btnEditPermRole" href="#" class="btn ubirimi-btn disabled"><i class="icon-edit"></i> Edit</a></td>
                    <td><a id="btnAssignUsersToRole" href="#" class="btn ubirimi-btn disabled">Default Users</a></td>
                    <td><a id="btnAssignGroupsToRole" href="#" class="btn ubirimi-btn disabled">Default Groups</a></td>
                    <td><a id="btnDeletePermRole" href="#" class="btn ubirimi-btn disabled"><i class="icon-remove"></i> Delete</a></td>
                </tr>
            </table>

            <?php $roles = UbirimiContainer::get()['repository']->get(Role::class)->getByClient($session->get('client/id')); ?>

            <?php if ($roles): ?>
                <table class="table table-hover table-condensed">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Name</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($role = $roles->fetch_array(MYSQLI_ASSOC)): ?>
                            <tr id="table_row_<?php echo $role['id'] ?>">
                                <td width="22">
                                    <input type="checkbox" value="1" id="el_check_<?php echo $role['id'] ?>" />
                                </td>
                                <td><?php echo $role['name']; ?></td>
                                <td><?php echo $role['description']; ?></td>
                            </tr>
                        <?php endwhile ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="messageGreen">There are no roles defined.</div>
            <?php endif ?>
            <div id="deletePermRole"></div>
            <div id="assignRoleUsers"></div>
            <div id="assignRoleGroups"></div>
        <?php else: ?>
            <?php Util::renderContactSystemAdministrator() ?>
        <?php endif ?>
    </div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>