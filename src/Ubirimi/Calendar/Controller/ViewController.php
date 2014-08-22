<?php

namespace Ubirimi\Calendar\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Calendar\Repository\Calendar;
use Ubirimi\Calendar\Repository\CalendarEvent;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class ViewController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_CALENDAR);

        $calendarIdsString = $request->get('ids');
        $calendarIds = explode('|', $calendarIdsString);

        $month = $request->get('month');
        $year = $request->get('year');

        $menuSelectedCategory = 'calendars';
        $defaultCalendarSelected = false;
        $calendarDefault = Calendar::getDefaultCalendar($session->get('user/id'));

        $calendarDefaultId = $calendarDefault['id'];
        if (in_array($calendarDefaultId, $calendarIds)) {
            $defaultCalendarSelected = true;
        }

        // check to see if each calendar belongs to the client
        for ($i = 0; $i < count($calendarIds); $i++) {

            $calendarFilter = Calendar::getById($calendarIds[$i]);

            if ($calendarFilter['client_id'] != $session->get('client/id')) {
                return new RedirectResponse('/general-settings/bad-link-access-denied');
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

        $calendarEvents = CalendarEvent::getByCalendarId(
            implode(', ', $calendarIds),
            $filterStartDate,
            $filterEndDate,
            $defaultCalendarSelected,
            $session->get('user/id'),
            'array'
        );

        $calendars = Calendar::getByUserId($session->get('user/id'), 'array');

        $calendarsSharedWithMe = Calendar::getSharedWithUserId($session->get('user/id'), 'array');

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

        return $this->render(__DIR__ . '/../Resources/views/View.php', get_defined_vars());
    }
}
