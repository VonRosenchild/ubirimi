<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Notification\NotificationScheme;

    Util::checkUserIsLoggedInAndRedirect();

    $emptyName = false;

    if (isset($_POST['add_notification_scheme'])) {
        $name = Util::cleanRegularInputField($_POST['name']);
        $description = Util::cleanRegularInputField($_POST['description']);

        if (empty($name))
            $emptyName = true;

        if (!$emptyName) {
            $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));
            $notificationScheme = new NotificationScheme($clientId, $name, $description);
            $notificationSchemeId = $notificationScheme->save($currentDate);

            Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'ADD Yongo Notification Scheme ' . $name, $currentDate);

            header('Location: /yongo/administration/notification-schemes');
        }
    }
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Issue Notification Scheme';

    require_once __DIR__ . '/../../../Resources/views/administration/notification_scheme/Add.php';