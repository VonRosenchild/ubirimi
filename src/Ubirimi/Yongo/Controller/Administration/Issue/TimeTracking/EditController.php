<?php
    use Ubirimi\Repository\Client;
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $menuSelectedCategory = 'system';
    $timeTrackingFlag = $session->get('yongo/settings/time_tracking_flag');
    $defaultTimeTracking = null;

    switch ($session->get('yongo/settings/time_tracking_default_unit')) {
        case 'w':
            $defaultTimeTracking = 'week';
            break;
        case 'd':
            $defaultTimeTracking = 'day';
            break;
        case 'h':
            $defaultTimeTracking = 'hours';
            break;
        case 'm':
            $defaultTimeTracking = 'minute';
            break;
    }

    if (isset($_POST['edit_time_tracking'])) {
        $hoursPerDay = $_POST['hours_per_day'];
        $daysPerWeek = $_POST['days_per_week'];
        $defaultUnit = $_POST['default_unit'];

        Client::updateTimeTrackingSettings($clientId, $hoursPerDay, $daysPerWeek, $defaultUnit);
        $currentDate = Util::getServerCurrentDateTime();
        Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'UPDATE Yongo Time Tracking Settings', $currentDate);

        $session->set('yongo/settings/time_tracking_hours_per_day', $hoursPerDay);
        $session->set('yongo/settings/time_tracking_days_per_week', $daysPerWeek);
        $session->set('yongo/settings/time_tracking_default_unit', $defaultUnit);

        header('Location: /yongo/administration/issue-features/time-tracking');
    }

    require_once __DIR__ . '/../../../../Resources/views/administration/issue/time_tracking/Edit.php';