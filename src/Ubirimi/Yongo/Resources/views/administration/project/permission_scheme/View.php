<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Yongo\Repository\Permission\Permission;
use Ubirimi\Yongo\Repository\Permission\PermissionScheme;

require_once __DIR__ . '/../../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../_menu.php'; ?>
    <div class="headerPageBackground">
        <table width="100%">
            <tr>
                <td width="48px">
                    <img class="projectIcon" src="/img/project.png" height="48px"/>
                </td>
                <td>
                    <div class="headerPageText"><a href="/yongo/administration/projects" class="linkNoUnderline">Projects</a> > <?php echo $project['name'] ?> > Permissions > <?php echo $permissionScheme['name'] ?></div>
                </td>
                <td align="right">
                    <?php require_once __DIR__ . '/../_main_actions.php' ?>
                </td>
            </tr>
        </table>
    </div>
    <div class="pageContent">

        <ul class="nav nav-tabs" style="padding: 0px;">
            <li><a href="/yongo/administration/project/<?php echo $projectId ?>">Summary</a></li>
            <li><a href="/yongo/administration/project/issue-types/<?php echo $projectId ?>">Issue Types</a></li>
            <li><a href="/yongo/administration/project/workflows/<?php echo $projectId ?>">Workflows</a></li>
            <li><a href="/yongo/administration/project/screens/<?php echo $projectId ?>">Screens</a></li>
            <li><a href="/yongo/administration/project/fields/<?php echo $projectId ?>">Fields</a></li>
            <li><a href="/yongo/administration/project/people/<?php echo $projectId ?>">People</a></li>
            <li class="active"><a href="/yongo/administration/project/permissions/<?php echo $projectId ?>">Permissions</a></li>
            <li><a href="/yongo/administration/project/issue-security/<?php echo $projectId ?>">Issue Security</a></li>
            <li><a href="/yongo/administration/project/notifications/<?php echo $projectId ?>">Notifications</a></li>
            <li><a href="/yongo/administration/project/versions/<?php echo $projectId ?>">Versions</a></li>
            <li><a href="/yongo/administration/project/components/<?php echo $projectId ?>">Components</a></li>
            <li><a href="/yongo/administration/project/helpdesk/<?php echo $projectId ?>">Helpdesk</a></li>
        </ul>

        <?php if ($hasGlobalAdministrationPermission || $hasGlobalSystemAdministrationPermission): ?>
            <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
                <tr>
                    <td><a href="/yongo/administration/permission-scheme/edit/<?php echo $permissionScheme['id'] ?>?back=view_project_permission&project_id=<?php echo $projectId ?>" class="btn ubirimi-btn"><i class="icon-edit"></i> Edit Permissions</a></td>
                    <td><a href="/yongo/administration/project/permissions/select-project-permission-scheme/<?php echo $projectId ?>" class="btn ubirimi-btn">Use a Different Scheme</a></td>
                </tr>
            </table>
        <?php endif ?>
        <div class="infoBox">
            <div>Project permissions allow you to control who can access your project, and what they can do, e.g. "Work on Issues". Access to individual issues is granted to people by issue permissions.</div>
            <div>The permission scheme defines how the permissions are configured for this project. To change the permisisons, you can select a different permission scheme, or modify the currently selected scheme.</div>
        </div>
        <?php while ($category = $permissionCategories->fetch_array(MYSQLI_ASSOC)): ?>
            <table class="table table-hover table-condensed">
                <thead>
                    <tr>
                        <th><?php echo $category['name'] ?></th>
                        <th width="40%">Users / Groups / Project Roles</th>
                    </tr>
                </thead>
                <?php $permissions = UbirimiContainer::get()['repository']->get(Permission::class)->getByCategory($category['id']); ?>
                <?php while ($permission = $permissions->fetch_array(MYSQLI_ASSOC)): ?>
                    <tr>
                        <td valign="top">
                            <?php echo $permission['name'] ?>
                            <div class="smallDescription"><?php echo $permission['description'] ?></div>
                        </td>
                        <td>
                            <?php
                                $permData = UbirimiContainer::get()['repository']->get(PermissionScheme::class)->getDataByPermissionId($permissionScheme['id'], $permission['id']);

                                if ($permData) {
                                    echo '<ul>';
                                    while ($data = $permData->fetch_array(MYSQLI_ASSOC)) {
                                        if ($data['user_id']) {
                                            echo '<li>Single User (' . $data['first_name'] . ' ' . $data['last_name'] . ')</li>';
                                        }
                                        if ($data['group_id']) {
                                            echo '<li>Group (' . $data['group_name'] . ')</li>';
                                        }
                                        if ($data['permission_role_id']) {
                                            echo '<li>Project Role (' . $data['permission_role_name'] . ')</li>';
                                        }
                                        if ($data['reporter']) {
                                            echo '<li>Reporter</li>';
                                        }
                                        if ($data['current_assignee']) {
                                            echo '<li>Current Assignee</li>';
                                        }
                                    }
                                    echo '</ul>';
                                }
                            ?>
                        </td>
                    </tr>
                <?php endwhile ?>
            </table>
        <?php endwhile ?>
    </div>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>