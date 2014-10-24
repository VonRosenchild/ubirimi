<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Yongo\Repository\Project\YongoProject;

require_once __DIR__ . '/../_header.php';
?>
<body>
    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <div class="headerPageBackground">
        <table width="100%">
            <tr>
                <td width="48px">
                    <img class="projectIcon" src="/img/project.png" height="48px"/>
                </td>
                <td>
                    <div class="headerPageText">
                        <a class="linkNoUnderline" href="/yongo/administration/projects">Projects</a> > <?php echo $project['name'] ?> > People
                    </div>
                </td>
                <td align="right">
                    <?php require_once __DIR__ . '/_main_actions.php' ?>
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
            <li class="active"><a href="/yongo/administration/project/people/<?php echo $projectId ?>">People</a></li>
            <li><a href="/yongo/administration/project/permissions/<?php echo $projectId ?>">Permissions</a></li>
            <li><a href="/yongo/administration/project/issue-security/<?php echo $projectId ?>">Issue Security</a></li>
            <li><a href="/yongo/administration/project/notifications/<?php echo $projectId ?>">Notifications</a></li>
            <li><a href="/yongo/administration/project/versions/<?php echo $projectId ?>">Versions</a></li>
            <li><a href="/yongo/administration/project/components/<?php echo $projectId ?>">Components</a></li>
            <li><a href="/yongo/administration/project/helpdesk/<?php echo $projectId ?>">Helpdesk</a></li>
        </ul>

        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <td><a id="btnManageUsersInProjectRole" href="#" class="btn ubirimi-btn disabled">Manage Users</a></td>
                <td><a id="btnManageGroupsInProjectRole" href="#" class="btn ubirimi-btn disabled">Manage Groups</a></td>
            </tr>
        </table>

        <table class="table table-hover table-condensed">
            <thead>
                <tr>
                    <th></th>
                    <th>Project Roles</th>
                    <th width="40%">Users</th>
                    <th width="40%">Groups</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($role = $roles->fetch_array(MYSQLI_ASSOC)): ?>
                    <tr id="table_row_<?php echo $role['id'] ?>">
                        <td width="22"><input type="checkbox" value="1" id="el_check_<?php echo $role['id'] ?>"/></td>
                        <td width="200"><?php echo $role['name'] ?></td>
                        <td>
                            <?php
                                $users = UbirimiContainer::get()['repository']->get(YongoProject::class)->getUsersInRole($projectId, $role['id']);
                                $usersNames = array();
                                while ($users && $user = $users->fetch_array(MYSQLI_ASSOC))
                                    $usersNames[] = $user['first_name'] . ' ' . $user['last_name']
                            ?>
                            <span><?php echo implode(', ', $usersNames) ?></span>
                        </td>
                        <td>
                            <?php
                                $groups = UbirimiContainer::get()['repository']->get(YongoProject::class)->getGroupsInRole($projectId, $role['id']);
                                $groupNames = array();
                                while ($groups && $group = $groups->fetch_array(MYSQLI_ASSOC)) {
                                    $groupNames[] = $group['group_name'];
                                }
                            ?>
                            <span><?php echo implode(', ', $groupNames) ?></span>

                        </td>
                    </tr>
                <?php endwhile ?>
            </tbody>
        </table>
        <input type="hidden" value="<?php echo $projectId ?>" id="project_id"/>

        <div id="assignProjectUsersToRole"></div>
        <div id="assignProjectGroupsToRole"></div>
    </div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>