<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Yongo\Repository\Notification\NotificationScheme;

require_once __DIR__ . '/../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <div class="headerPageBackground">
        <table width="100%">
            <tr>
                <td>
                    <div class="headerPageText">
                        <a class="linkNoUnderline" href="/yongo/administration/notification-schemes">Notification
                            Schemes</a> > <?php echo $notificationScheme['name'] ?> > Edit
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
                        <a class="btn ubirimi-btn"
                           href="/yongo/administration/project/notifications/<?php echo $projectId ?>">Go Back</a>
                    <?php else: ?>
                        <a class="btn ubirimi-btn" href="/yongo/administration/notification-schemes">Go Back</a>
                    <?php endif ?>
                </td>
            </tr>
        </table>

        <table class="table table-hover table-condensed">
            <thead>
            <tr>
                <th width="40%">Event</th>
                <th width="20%">Notifications</th>
                <th width="20%">Operations</th>
            </tr>
            </thead>

            <?php while ($event = $events->fetch_array(MYSQLI_ASSOC)): ?>
                <tr>
                    <td valign="top">
                        <b><?php echo $event['name'] ?></b>

                        <div class="smallDescription"><?php echo $event['description'] ?></div>
                    </td>
                    <td>
                        <?php
                        $notificationData = UbirimiContainer::get()['repository']->get(NotificationScheme::class)->getDataByNotificationSchemeIdAndEventId($notificationSchemeId, $event['id']);
                        if ($notificationData) {
                            echo '<ul>';
                            while ($data = $notificationData->fetch_array(MYSQLI_ASSOC)) {
                                $link_delete = '<a id="delete_notification_data_' . $data['id'] . '" href="#">remove</a>';
                                if ($data['current_assignee']) {
                                    echo '<li>Current Assignee (' . $link_delete . ')</li>';
                                } else if ($data['reporter']) {
                                    echo '<li>Reporter (' . $link_delete . ')</li>';
                                } else if ($data['current_user']) {
                                    echo '<li>Current user (' . $link_delete . ')</li>';
                                } else if ($data['project_lead']) {
                                    echo '<li>Project Lead (' . $link_delete . ')</li>';
                                } else if ($data['component_lead']) {
                                    echo '<li>Component Lead (' . $link_delete . ')</li>';
                                } else if ($data['first_name']) {
                                    echo '<li>Single User (' . $data['first_name'] . ' ' . $data['last_name'] . ') (' . $link_delete . ')</li>';
                                } else if ($data['group_name']) {
                                    echo '<li> Group (' . $data['group_name'] . ') (' . $link_delete . ')</li>';
                                } else if ($data['role_name']) {
                                    echo '<li>Project Role (' . $data['role_name'] . ') (' . $link_delete . ')</li>';
                                } else if ($data['all_watchers']) {
                                    echo '<li>All Watchers (' . $link_delete . ')</li>';
                                } else if ($data['custom_field_name']) {
                                    echo '<li>User Custom Field Value (' . $data['custom_field_name'] . ') (' . $link_delete . ')</li>';
                                }
                            }
                            echo '</ul>';
                        }
                        ?>
                    </td>
                    <td>
                        <a href="/yongo/administration/notification-scheme/add-data/<?php echo $notificationScheme['id'] ?>?id=<?php echo $event['id'] ?>">Add</a>
                    </td>
                </tr>
            <?php endwhile ?>
        </table>
        <div id="deleteNotificationDataModal" class="ubirimiModalDialog"></div>
    </div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>
</html>