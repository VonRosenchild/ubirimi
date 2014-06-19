<?php
    use Ubirimi\Calendar\Repository\Calendar;
    use Ubirimi\Calendar\Repository\CalendarEvent;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_CALENDAR);

    $calendarIdsString = $_GET['ids'];
    $calendarIds = explode('|', $calendarIdsString);

    $month = $_GET['month'];
    $year = $_GET['year'];

    $menuSelectedCategory = 'calendars';
    $defaultCalendarSelected = false;
    $calendarDefault = Calendar::getDefaultCalendar($loggedInUserId);

    $calendarDefaultId = $calendarDefault['id'];
    if (in_array($calendarDefaultId, $calendarIds)) {
        $defaultCalendarSelected = true;
    }

    // check to see if each calendar belongs to the client
    for ($i = 0; $i < count($calendarIds); $i++) {

        $calendarFilter = Calendar::getById($calendarIds[$i]);

        if ($calendarFilter['client_id'] != $clientId) {
            header('Location: /general-settings/bad-link-access-denied');
            die();
        }
    }
    $calendar = Calendar::getByIds(implode(', ', $calendarIds));
    $filterStartDate = $year . '-';
    if ($month < 10) {
        $filterStartDate .= '0' . $month;
    } else {
        $filterStartDate .= $month;
    }
    $filterStartDate .= '-01';
    $filterEndDate = date("Y-m-t", strtotime($filterStartDate));
    $calendarEvents = CalendarEvent::getByCalendarId(implode(', ', $calendarIds), $filterStartDate, $filterEndDate, $defaultCalendarSelected, $loggedInUserId, 'array');

    $calendars = Calendar::getByUserId($loggedInUserId, 'array');

    $calendarsSharedWithMe = Calendar::getSharedWithUserId($loggedInUserId, 'array');

    $currentMonthName = date("F", mktime(0, 0, 0, $month, 1, $year));

    $previousMonth = $month - 1;
    $previousYear = $year;
    $nextYear = $year;
    if ($previousMonth == 0) {
        $previousMonth = 12;
        $previousYear = $year - 1;
    }
    $nextMonth = $month + 1;
    if ($nextMonth == 13) {
        $nextMonth = 1;
        $nextYear = $year + 1;
    }

    $headings = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
    $running_day = date('w', mktime(0, 0, 0, $month, 1, $year));
    $days_in_month = date('t', mktime(0, 0, 0, $month, 1, $year));
    $days_in_this_week = 1;
    $day_counter = 0;
    $maxLevel = 0;
    $eventData = array();

    $daysInPreviousMonth = cal_days_in_month(CAL_GREGORIAN, $previousMonth, $year);

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_CALENDAR_NAME . ' / ' . $calendar['name'];

    require_once __DIR__ . '/../Resources/views/View.php';