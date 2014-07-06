<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Notification\NotificationScheme;

    Util::checkUserIsLoggedInAndRedirect();

    $notificationSchemeId = $_POST['id'];
    $notificationScheme = NotificationScheme::getMetaDataById($notificationSchemeId);

    NotificationScheme::deleteDataByNotificationSchemeId($notificationSchemeId);
    NotificationScheme::deleteById($notificationSchemeId);

    $currentDate = Util::getServerCurrentDateTime();
    Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'DELETE Yongo Notification Scheme ' . $notificationScheme['name'], $currentDate);