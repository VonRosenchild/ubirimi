<?php
    use Ubirimi\Repository\Client;
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    Client::toggleTimeTrackingFeature($clientId);
    $session->set('yongo/settings/time_tracking_flag', 1 - $session->get('yongo/settings/time_tracking_flag'));
    $logText = 'Activate';
    if (0 == $session->get('yongo/settings/time_tracking_flag')) {
        $logText = 'Deactivate';
    }

    $currentDate = Util::getServerCurrentDateTime();
    Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, $logText . ' Yongo Time Tracking', $currentDate);

    header('Location: /yongo/administration/issue-features/time-tracking');