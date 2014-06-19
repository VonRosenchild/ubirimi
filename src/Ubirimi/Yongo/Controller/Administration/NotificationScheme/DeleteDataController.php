<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Notification\NotificationScheme;

    Util::checkUserIsLoggedInAndRedirect();

    $notificationSchemeDataId = $_POST['notification_scheme_data_id'];
    NotificationScheme::deleteDataById($notificationSchemeDataId);

    $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));
    Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'DELETE Yongo Notification Scheme Data', $currentDate);