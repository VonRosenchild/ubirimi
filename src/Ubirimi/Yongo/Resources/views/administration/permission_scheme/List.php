<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Project\YongoProject;

require_once __DIR__ . '/../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <?php if (Util::userHasYongoAdministrativePermission()): ?>
        <?php Util::renderBreadCrumb('Permission Schemes') ?>
    <?php endif ?>
    <div class="pageContent">
        <?php if (Util::userHasYongoAdministrativePermission()): ?>

        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <td><a id="btnNew" href="/yongo/administration/permission-scheme/add" class="btn ubirimi-btn"><i class="icon-plus"></i> Create Permission Scheme</a></td>
                <td><a id="btnPermissions" href="#" class="btn ubirimi-btn disabled">Permissions</a></td>
                <td><a id="btnEditPermissionScheme" href="#" class="btn ubirimi-btn disabled"><i class="icon-edit"></i> Edit</a></td>
                <td><a id="btnCopyPermissionScheme" href="#" class="btn ubirimi-btn disabled">Copy</a></td>
                <td><a id="btnDeletePermissionScheme" href="#" class="btn ubirimi-btn disabled"><i class="icon-remove"></i> Delete</a></td>
            </tr>
        </table>
        <?php if ($permissionSchemes): ?>
            <table class="table table-hover table-condensed">
                <thead>
                    <tr>
                        <th></th>
                        <th>Name</th>
                        <th>Projects</th>
                        <th>Options</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($scheme = $permissionSchemes->fetch_array(MYSQLI_ASSOC)): ?>
                        <tr id="table_row_<?php echo $scheme['id'] ?>">
                            <td width="22">
                                <input type="checkbox" value="1" id="el_check_<?php echo $scheme['id'] ?>"/>
                            </td>
                            <td>
                                <a href="/yongo/administration/permission-scheme/edit/<?php echo $scheme['id'] ?>"><?php echo $scheme['name']; ?></a>
                                <br />
                                <?php echo $scheme['description'] ?>
                            </td>
                            <td width="500px">
                                <?php
                                    $projects = UbirimiContainer::get()['repository']->get(YongoProject::class)->getByPermissionScheme($scheme['id']);
                                    if ($projects) {
                                        echo '<ul>';
                                        while ($project = $projects->fetch_array(MYSQLI_ASSOC)) {
                                            echo '<li><a href="/yongo/administration/project/' . $project['id'] . '">' . $project['name'] . '</a></li>';
                                        }
                                        echo '</ul>';
                                        echo '<input type="hidden" id="delete_possible_' . $scheme['id'] . '" value="0">';
                                    } else {
                                        echo '<input type="hidden" id="delete_possible_' . $scheme['id'] . '" value="1">';
                                    }
                                ?>
                            </td>
                            <td>
                                <a href="/yongo/administration/permission-scheme/edit/<?php echo $scheme['id']?>">Permissions</a>
                                &middot;
                                <a href="/yongo/administration/permission-scheme/edit-metadata/<?php echo $scheme['id']?>">Edit</a>
                                &middot;
                                <a href="/yongo/administration/permission-scheme/copy/<?php echo $scheme['id']?>">Copy</a>
                            </td>
                        </tr>
                    <?php endwhile ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="messageGreen">There are no permission schemes defined.</div>
        <?php endif ?>
    </div>
        <div class="ubirimiModalDialog" id="modalDeletePermissionScheme"></div>
    <?php else: ?>
        <?php Util::renderContactSystemAdministrator() ?>
    <?php
        endif ?>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>
</html>