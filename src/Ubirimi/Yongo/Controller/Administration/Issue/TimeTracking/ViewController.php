<?php
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

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Time Tracking Settings';

    require_once __DIR__ . '/../../../../Resources/views/administration/issue/time_tracking/View.php';