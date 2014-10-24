<?php

namespace Ubirimi\Calendar\Controller\Event;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Calendar\Repository\Calendar\UbirimiCalendar;
use Ubirimi\Calendar\Repository\Event\CalendarEvent;
use Ubirimi\Calendar\Repository\Reminder\RepeatCycle;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class EditController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $clientId = $session->get('client/id');
        $clientSettings = $this->getRepository(UbirimiClient::class)->getSettings($clientId);

        $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_CALENDAR);

        $eventId = $request->get('id');
        $sourcePageLink = $request->get('source');
        $event = $this->getRepository(CalendarEvent::class)->getById($eventId, 'array');
        $eventRepeatData = $this->getRepository(CalendarEvent::class)->getRepeatDataById($event['cal_event_repeat_id']);

        $defaultEventStartDate = $eventRepeatData['start_date'];
        $defaultEventRepeatCycle = $eventRepeatData['cal_event_repeat_cycle_id'];
        $defaultEventRepeatEvery = $eventRepeatData['repeat_every'];
        $defaultEventEndAfterOccurrences = $eventRepeatData['end_after_occurrences'];
        $defaultEventEndDate = substr($eventRepeatData['end_date'], 0, 10);

        $repeatDaily = ($defaultEventRepeatCycle == RepeatCycle::REPEAT_DAILY);
        $repeatWeekly = ($defaultEventRepeatCycle == RepeatCycle::REPEAT_WEEKLY);

        $eventReminders = $this->getRepository(CalendarEvent::class)->getReminders($eventId);
        if ($event['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }
        $calendars = $this->getRepository(UbirimiCalendar::class)->getByUserId($session->get('user/id'), 'array');
        $menuSelectedCategory = 'calendars';

        if ($request->request->has('edit_event')) {

            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));
            $location = Util::cleanRegularInputField($request->request->get('location'));
            $calendarId = Util::cleanRegularInputField($request->request->get('calendar'));
            $dateFrom = Util::cleanRegularInputField($request->request->get('date_from'));
            $dateTo = Util::cleanRegularInputField($request->request->get('date_to'));
            $color = Util::cleanRegularInputField($request->request->get('color'));
            $eventRepeatType = Util::cleanRegularInputField($request->request->get('add_event_repeat_type'));

            $changeType = Util::cleanRegularInputField($request->request->get('change_event'));

            $dateFrom .= ':00';
            $dateTo .= ':00';
            $date = Util::getServerCurrentDateTime();

            $repeatData = '';
            // create repeat information string
            if ($eventRepeatType != -1) {

                switch ($eventRepeatType) {
                    case RepeatCycle::REPEAT_DAILY:
                        $repeatData .= '1';
                        $repeatData .= '#' . $request->request->get('add_event_repeat_every_daily');

                        if ('never' == $request->request->get('repeat_data_daily')) {
                            $repeatData .= '#n';
                        } else if ('after' == $request->request->get('repeat_data_daily')) {
                            $repeatData .= '#a' . $request->request->get('add_event_repeat_after_daily');
                        } else if ('on' == $request->request->get('repeat_data_daily')) {
                            $repeatData .= '#o' . $request->request->get('add_event_repeat_end_date_on_daily');
                        }
                        $repeatData .= '#' . $dateFrom;
                        break;

                    case RepeatCycle::REPEAT_WEEKLY:
                        $repeatData .= '2';
                        $repeatData .= $request->request->get('add_event_repeat_every_weekly');

                        if ('never' == $request->request->get('repeat_data_weekly')) {
                            $repeatData .= '#n';
                        } else if ('after' == $request->request->get('repeat_data_weekly')) {
                            $repeatData .= '#a' . $request->request->get('add_event_repeat_after_weekly');
                        } else if ('on' == $request->request->get('repeat_data_weekly')) {
                            $repeatData .= '#o' . $request->request->get('add_event_repeat_end_date_on_weekly');
                        }

                        $repeatData .= $request->request->get('add_event_repeat_start_date_weekly');
                        for ($i = 0; $i <= 6; $i++) {
                            $fieldName = 'week_on_' . $i;
                            if ($request->request->has($fieldName)) {
                                $repeatData .= '#1';
                            } else {
                                $repeatData .= '#0';
                            }
                        }

                        break;
                }
            }

            if (isset($changeType)) {
                if ('this_event' == $changeType) {
                    /*
                     * check to see if other events are linked to this event.
                     * if yes update those events to be linked to the following event in the series
                     * update current event and remove the repeat and link information
                     */
                    $eventsLinked = $this->getRepository(UbirimiCalendar::class)->getEventsByLinkId($event['cal_event_link_id']);
                    if ($eventsLinked) {
                        $nextEvent = $eventsLinked->fetch_array(MYSQLI_ASSOC);
                        $this->getRepository(UbirimiCalendar::class)->updateEventsLinkByLinkId($event['cal_event_link_id'], $nextEvent['cal_event_link_id']);
                    }
                    $this->getRepository(CalendarEvent::class)->updateRemoveLinkAndRepeat($eventId, $dateFrom, $dateTo);
                } else if ("following_events" == $changeType) {

                    if ($repeatData) {
                        /*
                         * delete all following events
                         * add this new event as a stand alone event
                         */

                        $this->getRepository(CalendarEvent::class)->deleteEventAndFollowingByLinkId($eventId);

                        $this->getRepository(CalendarEvent::class)->add(
                            $calendarId,
                            $session->get('user/id'),
                            $name,
                            $description,
                            $location,
                            $dateFrom,
                            $dateTo,
                            $color,
                            $date,
                            $repeatData,
                            $clientSettings
                        );
                    }
                } else if ("all_events" == $changeType) {
                    $eventLink = $this->getRepository(CalendarEvent::class)->getById($event['cal_event_link_id'], 'array');

                    $repeatDataPieces = explode('#', $repeatData);
                    array_pop($repeatDataPieces);
                    $repeatDataPieces[] = $eventLink['date_from'];
                    $repeatData = implode("#", $repeatDataPieces);

                    // delete current event and create new one
                    $this->getRepository(CalendarEvent::class)->deleteById($eventId, 'all_series');

                    $this->getRepository(CalendarEvent::class)->add(
                        $calendarId,
                        $session->get('user/id'),
                        $name,
                        $description,
                        $location,
                        $eventLink['date_from'],
                        $eventLink['date_to'],
                        $color,
                        $date,
                        $repeatData,
                        $clientSettings
                    );
                }
            } else {
                $this->getRepository(CalendarEvent::class)->updateById(
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

                $this->getRepository(CalendarEvent::class)->deleteReminders($eventId);

                // reminder information
                foreach ($request->request as $key => $value) {
                    if (strpos($key, 'reminder_type_') !== false) {
                        $indexReminder = str_replace('reminder_type_', '', $key);
                        $reminderType = Util::cleanRegularInputField($request->request->get($key));
                        $reminderValue = $request->request->get('value_reminder_' . $indexReminder);
                        $reminderPeriod = $request->request->get('reminder_period_' . $indexReminder);

                        // add the reminder
                        if (is_numeric($reminderValue)) {
                            $this->getRepository(CalendarEvent::class)->addReminder($eventId, $reminderType, $reminderPeriod, $reminderValue);
                        }
                    }
                }
            }

            $this->getRepository(UbirimiLog::class)->add($session->get('client/id'), SystemProduct::SYS_PRODUCT_CALENDAR, $session->get('user/id'),'UPDATE EVENTS event ' . $name, $date);

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