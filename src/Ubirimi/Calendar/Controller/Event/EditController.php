<?php
    use Ubirimi\Calendar\Repository\Calendar;
    use Ubirimi\Calendar\Repository\CalendarEvent;
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_CALENDAR);

    $eventId = $_GET['id'];
    $sourcePageLink = $_GET['source'];
    $event = CalendarEvent::getById($eventId, 'array');

    $eventReminders = CalendarEvent::getReminders($eventId);
    if ($event['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }
    $calendars = Calendar::getByUserId($loggedInUserId, 'array');
    $menuSelectedCategory = 'calendars';

    if (isset($_POST['edit_event'])) {
        $name = Util::cleanRegularInputField($_POST['name']);
        $description = Util::cleanRegularInputField($_POST['description']);
        $location = Util::cleanRegularInputField($_POST['location']);
        $calendarId = Util::cleanRegularInputField($_POST['calendar']);
        $dateFrom = Util::cleanRegularInputField($_POST['date_from']);
        $dateTo = Util::cleanRegularInputField($_POST['date_to']);
        $color = Util::cleanRegularInputField($_POST['color']);

        $dateFrom .= ':00';
        $dateTo .= ':00';
        $date = Util::getCurrentDateTime($session->get('client/settings/timezone'));
        CalendarEvent::updateById($eventId, $calendarId, $name, $description, $location, $dateFrom, $dateTo, $color, $date);
        CalendarEvent::deleteReminders($eventId);

        // reminder information
        foreach ($_POST as $key => $value) {
            if (strpos($key, 'reminder_type_') !== false) {
                $indexReminder = str_replace('reminder_type_', '', $key);
                $reminderType = Util::cleanRegularInputField($_POST[$key]);
                $reminderValue = $_POST['value_reminder_' . $indexReminder];
                $reminderPeriod = $_POST['reminder_period_' . $indexReminder];

                // add the reminder
                if (is_numeric($reminderValue)) {
                    CalendarEvent::addReminder($eventId, $reminderType, $reminderPeriod, $reminderValue);
                }
            }
        }

        Log::add($clientId, SystemProduct::SYS_PRODUCT_CALENDAR, $loggedInUserId, 'UPDATE EVENTS event ' . $name, $date);

        header('Location: ' . $sourcePageLink);
    }

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_CALENDAR_NAME . ' / Event: ' . $event['name'] . ' / Update';

    require_once __DIR__ . '/../../Resources/views/event/Edit.php';