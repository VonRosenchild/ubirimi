<?php
    use Ubirimi\Repository\Group\Group;
    use Ubirimi\Repository\Log;
    use Ubirimi\Repository\User\User;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueEvent;
    use Ubirimi\Yongo\Repository\Notification\Notification;
    use Ubirimi\Yongo\Repository\Notification\NotificationScheme;
    use Ubirimi\Yongo\Repository\Permission\PermissionRole;

    Util::checkUserIsLoggedInAndRedirect();

    $notificationSchemeId = $_GET['not_scheme_id'];
    $eventId = isset($_GET['id']) ? $_GET['id'] : null;

    $notificationScheme = NotificationScheme::getMetaDataById($notificationSchemeId);

    $events = IssueEvent::getByClient($clientId);

    $users = User::getByClientId($clientId);
    $groups = Group::getByClientIdAndProductId($clientId, SystemProduct::SYS_PRODUCT_YONGO);
    $roles = PermissionRole::getByClient($clientId);

    if (isset($_POST['confirm_new_data'])) {

        $eventIds = $_POST['event'];
        $notificationType = ($_POST['type']) ? $_POST['type'] : null;

        $user = $_POST['user'];
        $group = $_POST['group'];
        $role = $_POST['role'];

        $currentDate = Util::getServerCurrentDateTime();

        if ($notificationType) {

            for ($i = 0; $i < count($eventIds); $i++) {
                // check for duplicate information
                $duplication = false;

                $dataNotification = NotificationScheme::getDataByNotificationSchemeIdAndEventId($notificationSchemeId, $eventIds[$i]);
                if ($dataNotification) {

                    while ($data = $dataNotification->fetch_array(MYSQLI_ASSOC)) {
                        if ($data['group_id'] && $data['group_id'] == $group)
                            $duplication = true;
                        if ($data['user_id'] && $data['user_id'] == $user)
                            $duplication = true;
                        if ($data['permission_role_id'] && $data['permission_role_id'] == $role)
                            $duplication = true;
                        if ($notificationType == Notification::NOTIFICATION_TYPE_PROJECT_LEAD)
                            if ($data['project_lead'])
                                $duplication = true;
                        if ($notificationType == Notification::NOTIFICATION_TYPE_COMPONENT_LEAD)
                            if ($data['component_lead'])
                                $duplication = true;
                        if ($notificationType == Notification::NOTIFICATION_TYPE_CURRENT_ASSIGNEE)
                            if ($data['current_assignee'])
                                $duplication = true;
                        if ($notificationType == Notification::NOTIFICATION_TYPE_CURRENT_USER)
                            if ($data['current_user'])
                                $duplication = true;
                        if ($notificationType == Notification::NOTIFICATION_TYPE_REPORTER)
                            if ($data['reporter'])
                                $duplication = true;
                        if ($notificationType == Notification::NOTIFICATION_TYPE_ALL_WATCHERS)
                            if ($data['all_watchers'])
                                $duplication = true;
                    }
                }
                if (!$duplication) {
                    NotificationScheme::addData($notificationSchemeId, $eventIds[$i], $notificationType, $user, $group, $role, $currentDate);

                    Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'ADD Yongo Notification Scheme Data', $currentDate);
                }
            }
        }

        header('Location: /yongo/administration/notification-scheme/edit/' . $notificationSchemeId);
    }
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Notification Data';

    require_once __DIR__ . '/../../../Resources/views/administration/notification_scheme/AddData.php';