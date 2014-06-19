<?php
    use Ubirimi\Calendar\Repository\Calendar;
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $calendarId = $_POST['id'];
    $calendar = Calendar::getById($calendarId);

    $date = Util::getCurrentDateTime($session->get('client/settings/timezone'));

    Calendar::deleteById($calendarId);

    Log::add($clientId, SystemProduct::SYS_PRODUCT_CALENDAR, $loggedInUserId, 'DELETE EVENTS calendar ' . $calendar['name'], $date);

    if ($calendar['default_flag']) {
        // create the default calendar again
        Calendar::save($loggedInUserId, $session->get('user/first_name') . ' ' . $session->get('user/last_name'), 'The primary calendar', '#A1FF9E', $date, 1);
    }