<?php

namespace Ubirimi\Calendar\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Calendar\Repository\Calendar\UbirimiCalendar;
use Ubirimi\Calendar\Repository\Event\CalendarEvent;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class ViewController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_CALENDAR);

        $myCalendarIds = $this->getRepository(UbirimiCalendar::class)->getByUserId($session->get('user/id'), 'array', 'id');
        $sharedCalendarsIds = $this->getRepository(UbirimiCalendar::class)->getSharedWithUserId($session->get('user/id'), 'array', 'id');
        if (!$sharedCalendarsIds) {
            $sharedCalendarsIds = array();
        }
        $calendarIdsString = $request->get('ids');
        $calendarIds = explode('|', $calendarIdsString);

        // remove those IDs that are not mine or shared with me
        for ($i = 0; $i < count($calendarIds); $i++) {
            if (!in_array($calendarIds[$i], $myCalendarIds) && (!in_array($calendarIds[$i], $sharedCalendarsIds))) {
                unset($calendarIds[$i]);
            }
        }

        $month = $request->get('month');
        $year = $request->get('year');

        $menuSelectedCategory = 'calendars';
        $defaultCalendarSelected = false;
        $calendarDefault = $this->getRepository(UbirimiCalendar::class)->getDefaultCalendar($session->get('user/id'));

        $calendarDefaultId = $calendarDefault['id'];
        if (in_array($calendarDefaultId, $calendarIds)) {
            $defaultCalendarSelected = true;
        }

        // check to see if each calendar belongs to the client
        for ($i = 0; $i < count($calendarIds); $i++) {
            $calendarFilter = $this->getRepository(UbirimiCalendar::class)->getById($calendarIds[$i]);

            if ($calendarFilter['client_id'] != $session->get('client/id')) {
                return new RedirectResponse('/general-settings/bad-link-access-denied');
            }
        }
        $calendar = $this->getRepository(UbirimiCalendar::class)->getByIds(implode(', ', $calendarIds));

        $clientSettings = $session->get('client/settings');
        $filterStartDate = new \DateTime("first day of last month", new \DateTimeZone($clientSettings['timezone']));
        $filterEndDate = new \DateTime("last day of next month", new \DateTimeZone($clientSettings['timezone']));

        $filterStartDate = $filterStartDate->format('Y-m-d');
        $filterEndDate = $filterEndDate->format('Y-m-d');

        $calendarEvents = $this->getRepository(CalendarEvent::class)->getByCalendarId(
            implode(', ', $calendarIds),
            $filterStartDate,
            $filterEndDate,
            $defaultCalendarSelected,
            $session->get('user/id'),
            'array'
        );

        $calendars = $this->getRepository(UbirimiCalendar::class)->getByUserId($session->get('user/id'), 'array');


        $calendarsSharedWithMe = $this->getRepository(UbirimiCalendar::class)->getSharedWithUserId($session->get('user/id'), 'array');

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
        $runningDay = date('w', mktime(0, 0, 0, $month, 1, $year));
        $daysInMonth = date('t', mktime(0, 0, 0, $month, 1, $year));
        $daysInThisWeek = 1;
        $dayCounter = 0;
        $maxLevel = 0;
        $eventData = array();

        $daysInPreviousMonth = cal_days_in_month(CAL_GREGORIAN, $previousMonth, $year);

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_CALENDAR_NAME . ' / ' . $calendar['name'];

        $dates = array();
        for ($x = 1; $x <= $runningDay; $x++) {
            $dayCell = ($daysInPreviousMonth - $runningDay + $x) . '_' . $previousMonth . '_' . $previousYear;
            $dates[] = $dayCell;
        }

        for ($list_day = 1; $list_day <= $daysInMonth; $list_day++) {
            $dayCell = $list_day . '_' . $month . '_' . $year;
            $dates[] = $dayCell;
        }

        if (count($dates) > 35) {
            $maximumDays = 42;
        } else {
            $maximumDays = 35;
        }
        $daysLeft = $maximumDays - count($dates);
        for ($x = 1; $x <= $daysLeft; $x++) {
            $dates[] = $x . '_' . $nextMonth . '_' . $nextYear;
        }

        $weeks = intval(count($dates) / 7);

        return $this->render(__DIR__ . '/../Resources/views/View.php', get_defined_vars());
    }
}