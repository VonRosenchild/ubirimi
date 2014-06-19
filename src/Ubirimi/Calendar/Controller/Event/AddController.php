<?php
    use Ubirimi\Calendar\Repository\Calendar;
    use Ubirimi\Calendar\Repository\CalendarEvent;
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $name = Util::cleanRegularInputField($_POST['name']);
    $description = $_POST['description'];
    $location = $_POST['location'];
    $calendarId = $_POST['calendar'];
    $start = $_POST['start'];
    $end = $_POST['end'];
    $color = $_POST['color'];
    $repeatData = isset($_POST['repeat_data']) ? $_POST['repeat_data'] : null;


    if (!empty($name)) {
        $date = Util::getCurrentDateTime($session->get('client/settings/timezone'));

        $eventId = CalendarEvent::add($calendarId, $loggedInUserId, $name, $description, $location, $start, $end, $color, $date, $repeatData);

        // add the default reminders
        $reminders = Calendar::getReminders($calendarId);
        while ($reminders && $reminder = $reminders->fetch_array(MYSQLI_ASSOC)) {

            CalendarEvent::addReminder($eventId, $reminder['cal_event_reminder_type_id'], $reminder['cal_event_reminder_period_id'], $reminder['value']);
        }

        Log::add($clientId, SystemProduct::SYS_PRODUCT_CALENDAR, $loggedInUserId, 'ADD EVENTS event ' . $name, $date);
    }