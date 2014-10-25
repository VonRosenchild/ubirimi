<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Permission\GlobalPermission;

require_once __DIR__ . '/../_header.php';
?>
<body>
    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <?php if (Util::userHasYongoAdministrativePermission()): ?>
        <?php Util::renderBreadCrumb('Global Permissions') ?>
    <?php endif ?>
    <div class="pageContent">
        <?php if (Util::userHasYongoAdministrativePermission()): ?>

            <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
                <tr>
                    <td><a href="/yongo/administration/global-permission/add" class="btn ubirimi-btn"><i class="icon-plus"></i> Add Permission</a></td>
                </tr>
            </table>

            <div class="infoBox">These permissions apply to all projects. They are independent of project specific permissions. If you wish to set permissions on a project-by-project basis you can set them up in the <a href="/yongo/administration/permission-schemes">Permission Schemes</a>.</div>

            <table class="table table-hover table-condensed">
                <thead>
                    <tr>
                        <th>Permissions</th>
                        <th>Users / Groups</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($globalPermission = $globalPermissions->fetch_array(MYSQLI_ASSOC)): ?>
                        <tr>
                            <td>
                                <?php echo $globalPermission['name'] ?>
                                <div class="smallDescription"><?php echo $globalPermission['description'] ?></div>
                            </td>

                            <td>
                                <?php
                                    $groups = UbirimiContainer::get()['repository']->get(GlobalPermission::class)->getDataByPermissionId($clientId, $globalPermission['id']);
                                    if ($groups) {
                                        echo '<ul>';
                                        while ($group = $groups->fetch_array(MYSQLI_ASSOC)) {
                                            echo '<li>' . $group['name'] . ' (<a href="/yongo/administration/users?group_id=' . $group['id'] . '">View Users</a> | <a href="/yongo/administration/global-permission/delete/' . $group['sys_permission_global_data_id'] . '">Delete</a>)</li>';
                                        }
                                        echo '</ul>';
                                    }
                                ?>
                            </td>
                        </tr>
                    <?php endwhile ?>
                </tbody>
            </table>
        <?php else: ?>
            <?php Util::renderContactSystemAdministrator() ?>
        <?php endif ?>
    </div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>