<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Notification\NotificationScheme;

    Util::checkUserIsLoggedInAndRedirect();

    $notificationSchemeId = $_GET['id'];
    $notificationScheme = NotificationScheme::getMetaDataById($notificationSchemeId);

    if ($notificationScheme['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    $emptyName = false;
    $duplicateName = false;

    if (isset($_POST['copy_notification_scheme'])) {
        $name = Util::cleanRegularInputField($_POST['name']);
        $description = Util::cleanRegularInputField($_POST['description']);

        if (empty($name)) {
            $emptyName = true;
        }

        $duplicateNotificationScheme = NotificationScheme::getMetaDataByNameAndClientId($clientId, mb_strtolower($name));
        if ($duplicateNotificationScheme)
            $duplicateName = true;

        if (!$emptyName && !$duplicateName) {
            $copiedNotificationScheme = new NotificationScheme($clientId, $name, $description);
            $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));
            $copiedNotificationSchemeId = $copiedNotificationScheme->save($currentDate);

            $notificationSchemeData = NotificationScheme::getDataByNotificationSchemeId($notificationSchemeId);
            while ($notificationSchemeData && $data = $notificationSchemeData->fetch_array(MYSQLI_ASSOC)) {
                $copiedNotificationScheme->addDataRaw($copiedNotificationSchemeId, $data['event_id'], $data['permission_role_id'], $data['group_id'], $data['user_id'],
                                                   $data['current_assignee'], $data['reporter'], $data['current_user'], $data['project_lead'], $data['component_lead'], $currentDate);
            }

            Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'Copy Yongo Notification Scheme ' . $notificationScheme['name'], $currentDate);

            header('Location: /yongo/administration/notification-schemes');
        }
    }
    $menuSelectedCategory = 'issue';

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Copy Notification Scheme';

    require_once __DIR__ . '/../../../Resources/views/administration/notification_scheme/Copy.php';