<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\LinkHelper;
use Ubirimi\SystemProduct;
use Ubirimi\Yongo\Repository\Permission\Permission;
use Ubirimi\Yongo\Repository\Permission\PermissionScheme;

require_once __DIR__ . '/../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <div class="headerPageBackground">
        <table width="100%">
            <tr>
                <td>
                    <div class="headerPageText">
                        <a class="linkNoUnderline" href="/yongo/administration/permission-schemes">Permission Schemes</a> > <?php echo $permissionScheme['name'] ?> > Edit
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <div class="pageContent">

        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <td>
                    <?php if (isset($backLink)): ?>
                        <a class="btn ubirimi-btn" href="/yongo/administration/project/permissions/<?php echo $projectId ?>">Go Back</a>
                    <?php else: ?>
                        <a class="btn ubirimi-btn" href="/yongo/administration/permission-schemes">Go Back</a>
                    <?php endif ?>
                </td>
            </tr>
        </table>

        <?php while ($category = $permissionCategories->fetch_array(MYSQLI_ASSOC)): ?>
            <br />
            <table class="table table-hover table-condensed">
                <thead>
                    <tr>
                        <th><?php echo $category['name'] ?></th>
                        <th width="40%">Users / Groups / Project Roles</th>
                        <th width="20%">Operations</th>
                    </tr>
                </thead>
                <?php $permissions = UbirimiContainer::get()['repository']->get(Permission::class)->getByCategory($category['id']); ?>
                <tbody>
                    <?php while ($permission = $permissions->fetch_array(MYSQLI_ASSOC)): ?>
                        <tr>
                            <td valign="top">
                                <b><?php echo $permission['name'] ?></b>

                                <div class="smallDescription"><?php echo $permission['description'] ?></div>
                            </td>
                            <td>
                                <?php
                                    $permData = UbirimiContainer::get()['repository']->get(PermissionScheme::class)->getDataByPermissionId($permissionScheme['id'], $permission['id']);

                                    if ($permData) {
                                        echo '<ul>';
                                            while ($data = $permData->fetch_array(MYSQLI_ASSOC)) {

                                                if ($data['current_assignee']) {
                                                    echo '<li>Current Assignee (<a href="#" id="perm_delete_' . $data['id'] . '">Delete</a>)</li>';
                                                } else if ($data['reporter']) {
                                                    echo '<li>Reporter (<a href="#" id="perm_delete_' . $data['id'] . '">Delete</a>)</li>';
                                                } else if ($data['project_lead']) {
                                                    echo '<li>Project Lead (<a href="#" id="perm_delete_' . $data['id'] . '">Delete</a>)</li>';
                                                }

                                                if ($data['user_id']) {
                                                    echo '<li>Single User (' . LinkHelper::getUserProfileLink($data['user_id'], SystemProduct::SYS_PRODUCT_YONGO, $data['first_name'], $data['last_name']) . ') (<a href="#" id="perm_delete_' . $data['id'] . '">Delete</a>)</li>';
                                                }

                                                if (isset($data['group_id'])) {
                                                    if (0 == $data['group_id']) {
                                                        echo '<li>Group (Anyone) (<a href="#" id="perm_delete_' . $data['id'] . '">Delete</a>)</li>';
                                                    } else {
                                                        echo '<li>Group (' . $data['group_name'] . ') (<a href="#" id="perm_delete_' . $data['id'] . '">Delete</a>)</li>';
                                                    }

                                                }
                                                if ($data['permission_role_id']) {
                                                    echo '<li>Project Role (' . $data['permission_role_name'] . ') (<a href="#" id="perm_delete_' . $data['id'] . '">Delete</a>)</li>';
                                                }
                                            }
                                        echo '</ul>';
                                    }
                                ?>
                            </td>
                            <td>

                                <a href="/yongo/administration/permission-scheme/add-data/<?php echo $permissionScheme['id'] ?>?id=<?php echo $permission['id'] ?>">Add</a>
                            </td>
                        </tr>
                    <?php endwhile ?>
                </tbody>
            </table>
        <?php endwhile ?>

        <input type="hidden" value="<?php echo $permissionScheme['id'] ?>" id="permission_scheme_id"/>

        <div id="definePermissionData"></div>
        <div id="deletePermissionData"></div>
    </div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>
</html>