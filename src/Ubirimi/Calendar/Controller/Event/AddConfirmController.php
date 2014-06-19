<?php
    use Ubirimi\Calendar\Repository\Calendar;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();
    $calendars = Calendar::getByUserId($loggedInUserId, 'array');
    $defaultDay = $_GET['day'];
    $defaultMonth = $_GET['month'];
    $defaultYear = $_GET['year'];

    $firstCalendar = $calendars[0];
    if ($defaultDay < 10) {
        $defaultDay = '0' . $defaultDay;
    }
    if ($defaultMonth < 10) {
        $defaultMonth = '0' . $defaultMonth;
    }
    $defaultDate = $defaultYear . '-' . $defaultMonth . '-' . $defaultDay . ' 00:00';

    require_once __DIR__ . '/../../Resources/views/event/AddConfirm.php';