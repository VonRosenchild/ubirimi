<?php

namespace Ubirimi\Calendar\Controller\Event;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Calendar\Repository\Calendar;
use Ubirimi\Calendar\Repository\CalendarEvent;
use Ubirimi\Repository\Log;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Repository\Client;

class AddController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $clientId = $session->get('client/id');
        $clientSettings = Client::getSettings($clientId);

        $name = Util::cleanRegularInputField($_POST['name']);
        $description = $request->request->get('description');
        $location = $request->request->get('location');
        $calendarId = $request->request->get('calendar');
        $start = $request->request->get('start');
        $end = $request->request->get('end');
        $color = $request->request->get('color');
        $repeatData = $request->request->get('repeat_data');

        if (!empty($name)) {
            $date = Util::getServerCurrentDateTime();

            ini_set('memory_limit', '1024M');

            $eventId = CalendarEvent::add(
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
            $reminders = Calendar::getReminders($calendarId);
            while ($reminders && $reminder = $reminders->fetch_array(MYSQLI_ASSOC)) {
                CalendarEvent::addReminder(
                    $eventId,
                    $reminder['cal_event_reminder_type_id'],
                    $reminder['cal_event_reminder_period_id'],
                    $reminder['value']
                );
            }

            Log::add(
                $session->get('client/id'),
                SystemProduct::SYS_PRODUCT_CALENDAR,
                $session->get('user/id'),
                'ADD EVENTS event ' . $name,
                $date
            );
        }

        return new Response('');
    }
}
