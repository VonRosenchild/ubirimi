<?php

namespace Ubirimi\Calendar\Controller\Event;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Calendar\Repository\Calendar;
use Ubirimi\Calendar\Repository\CalendarEvent;
use Ubirimi\Repository\Log;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Calendar\Repository\CalendarEventRepeatCycle;

class EditController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_CALENDAR);

        $eventId = $request->get('id');
        $sourcePageLink = $request->get('source');
        $event = CalendarEvent::getById($eventId, 'array');
        $eventRepeatData = CalendarEvent::getRepeatDataById($event['cal_event_repeat_id']);

        $defaultEventStartDate = $eventRepeatData['start_date'];
        $defaultEventRepeatCycle = $eventRepeatData['cal_event_repeat_cycle_id'];
        $defaultEventRepeatEvery = $eventRepeatData['repeat_every'];
        $defaultEventEndAfterOccurrences = $eventRepeatData['end_after_occurrences'];
        $defaultEventEndDate = substr($eventRepeatData['end_date'], 0, 10);

        $repeatDaily = ($defaultEventRepeatCycle == CalendarEventRepeatCycle::REPEAT_DAILY);
        $repeatWeekly = ($defaultEventRepeatCycle == CalendarEventRepeatCycle::REPEAT_WEEKLY);

        $eventReminders = CalendarEvent::getReminders($eventId);
        if ($event['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }
        $calendars = Calendar::getByUserId($session->get('user/id'), 'array');
        $menuSelectedCategory = 'calendars';

        if ($request->request->has('edit_event')) {

            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));
            $location = Util::cleanRegularInputField($request->request->get('location'));
            $calendarId = Util::cleanRegularInputField($request->request->get('calendar'));
            $dateFrom = Util::cleanRegularInputField($request->request->get('date_from'));
            $dateTo = Util::cleanRegularInputField($request->request->get('date_to'));
            $color = Util::cleanRegularInputField($request->request->get('color'));

            $changeType = Util::cleanRegularInputField($request->request->get('change_event'));
            $dateFrom .= ':00';
            $dateTo .= ':00';
            $date = Util::getServerCurrentDateTime();

            if (isset($changeType)) {
                if ('this_event' == $changeType) {
                    /*
                     * check to see if other events are linked to this event.
                     * if yes update those events to be linked to the following event in the series
                     * update current event and remove the repeat and link information
                     */
                    $eventsLinked = Calendar::getEventsByLinkId($event['cal_event_link_id']);
                    if ($eventsLinked) {
                        $nextEvent = $eventsLinked->fetch_array(MYSQLI_ASSOC);
                        Calendar::updateEventsLinkByLinkId($event['cal_event_link_id'], $nextEvent['cal_event_link_id']);
                    }
                    CalendarEvent::updateRemoveLinkAndRepeat($eventId, $dateFrom, $dateTo);
                }
            } else {
                CalendarEvent::updateById(
                    $eventId,
                    $calendarId,
                    $name,
                    $description,
                    $location,
                    $dateFrom,
                    $dateTo,
                    $color,
                    $date
                );
                CalendarEvent::deleteReminders($eventId);

                // reminder information
                foreach ($request->request as $key => $value) {
                    if (strpos($key, 'reminder_type_') !== false) {
                        $indexReminder = str_replace('reminder_type_', '', $key);
                        $reminderType = Util::cleanRegularInputField($request->request->get($key));
                        $reminderValue = $request->request->get('value_reminder_' . $indexReminder);
                        $reminderPeriod = $request->request->get('reminder_period_' . $indexReminder);

                        // add the reminder
                        if (is_numeric($reminderValue)) {
                            CalendarEvent::addReminder($eventId, $reminderType, $reminderPeriod, $reminderValue);
                        }
                    }
                }
            }

            Log::add($session->get('client/id'), SystemProduct::SYS_PRODUCT_CALENDAR, $session->get('user/id'),'UPDATE EVENTS event ' . $name, $date);

            return new RedirectResponse($sourcePageLink);
        }

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / '
            . SystemProduct::SYS_PRODUCT_CALENDAR_NAME
            . ' / Event: '
            . $event['name']
            . ' / Update';

        return $this->render(__DIR__ . '/../../Resources/views/event/Edit.php', get_defined_vars());
    }
}