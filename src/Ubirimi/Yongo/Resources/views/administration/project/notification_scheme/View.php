<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Yongo\Repository\Notification\NotificationScheme;

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
                    <div class="headerPageText"><a href="/yongo/administration/projects" class="linkNoUnderline">Projects</a> > <?php echo $project['name'] ?> > Notifications > <?php echo $notificationScheme['name'] ?></div>
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
            <li><a href="/yongo/administration/project/permissions/<?php echo $projectId ?>">Permissions</a></li>
            <li><a href="/yongo/administration/project/issue-security/<?php echo $projectId ?>">Issue Security</a></li>
            <li class="active"><a href="/yongo/administration/project/notifications/<?php echo $projectId ?>">Notifications</a></li>
            <li><a href="/yongo/administration/project/versions/<?php echo $projectId ?>">Versions</a></li>
            <li><a href="/yongo/administration/project/components/<?php echo $projectId ?>">Components</a></li>
            <li><a href="/yongo/administration/project/helpdesk/<?php echo $projectId ?>">Helpdesk</a></li>
        </ul>

        <?php if ($hasGlobalAdministrationPermission || $hasGlobalSystemAdministrationPermission): ?>
            <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
                <tr>
                    <td><a href="/yongo/administration/notification-scheme/edit/<?php echo $notificationScheme['id'] ?>?back=view_project_notification&project_id=<?php echo $projectId ?>" class="btn ubirimi-btn"><i class="icon-edit"></i> Edit Notifications</a></td>
                    <td><a href="/yongo/administration/project/notifications/select-project-notification-scheme/<?php echo $projectId ?>" class="btn ubirimi-btn">Use a Different Scheme</a></td>
                </tr>
            </table>
        <?php endif ?>
        <table class="table table-hover table-condensed">
            <thead>
                <tr>
                    <th width="600">Event</th>
                    <th>Notifications</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($notification = $events->fetch_array(MYSQLI_ASSOC)): ?>
                    <tr>
                        <td valign="top">
                            <?php echo $notification['name'] ?>
                            <div class="smallDescription"><?php echo $notification['description'] ?></div>
                        </td>
                        <td>
                            <?php
                                $notificationData = UbirimiContainer::get()['repository']->get(NotificationScheme::class)->getDataByNotificationSchemeIdAndEventId($notificationSchemeId, $notification['id']);
                                if ($notificationData) {
                                    echo '<ul>';
                                    while ($data = $notificationData->fetch_array(MYSQLI_ASSOC)) {
                                        if ($data['current_assignee']) {
                                            echo '<li>Current Assignee</li>';
                                        } else if ($data['reporter']) {
                                            echo '<li>Reporter</li>';
                                        } else if ($data['current_user']) {
                                            echo '<li>Current user)</li>';
                                        } else if ($data['project_lead']) {
                                            echo '<li>Project Lead</li>';
                                        } else if ($data['component_lead']) {
                                            echo '<li>Component Lead)</li>';
                                        } else if ($data['first_name']) {
                                            echo '<li>Single User (' . $data['first_name'] . ' ' . $data['last_name'] . ')</li>';
                                        } else if ($data['group_name']) {
                                            echo '<li> Group (' . $data['group_name'] . ')</li>';
                                        } else if ($data['role_name']) {
                                            echo '<li>Project Role (' . $data['role_name'] . ')</li>';
                                        } else if ($data['all_watchers']) {
                                            echo '<li>All Watchers</li>';
                                        } else if ($data['custom_field_name']) {
                                            echo '<li>User Custom Field Value (' . $data['custom_field_name'] . ')</li>';
                                        }
                                    }
                                    echo '</ul>';
                                }
                            ?>
                        </td>
                    </tr>
                <?php endwhile ?>
            </tbody>
        </table>
    </div>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>