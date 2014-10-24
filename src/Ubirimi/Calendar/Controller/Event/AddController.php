<?php

namespace Ubirimi\Calendar\Controller\Event;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Calendar\Repository\Calendar\UbirimiCalendar;
use Ubirimi\Calendar\Repository\Event\CalendarEvent;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class AddController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $clientId = $session->get('client/id');
        $clientSettings = $this->getRepository(UbirimiClient::class)->getSettings($clientId);

        $name = Util::cleanRegularInputField($request->request->get('name'));
        $description = $request->request->get('description');
        $location = $request->request->get('location');
        $calendarId = $request->request->get('calendar');
        $start = $request->request->get('start');
        $end = $request->request->get('end');
        $color = '#' . $request->request->get('color');
        $repeatData = $request->request->get('repeat_data');

        if (!empty($name)) {
            $date = Util::getServerCurrentDateTime();

            ini_set('memory_limit', '1024M');

            $eventId = $this->getRepository(CalendarEvent::class)->add(
                $calendarId,
                $session->get('user/id'),
                $name,
                $description,
                $location,
                $start,
                $end,
                $color,
                $date,
                $repeatData,
                $clientSettings
            );

            // add the default reminders
            $reminders = $this->getRepository(UbirimiCalendar::class)->getReminders($calendarId);
            while ($reminders && $reminder = $reminders->fetch_array(MYSQLI_ASSOC)) {
                $this->getRepository(CalendarEvent::class)->addReminder(
                    $eventId,
                    $reminder['cal_event_reminder_type_id'],
                    $reminder['cal_event_reminder_period_id'],
                    $reminder['value']
                );
            }

            $this->getRepository(UbirimiLog::class)->add($session->get('client/id'), SystemProduct::SYS_PRODUCT_CALENDAR, $session->get('user/id'),'ADD EVENTS event ' . $name, $date);
        }

        return new Response('');
    }
}