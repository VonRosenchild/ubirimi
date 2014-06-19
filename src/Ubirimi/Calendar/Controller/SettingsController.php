<?php
    use Ubirimi\Calendar\Repository\Calendar;
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $calendarId = $_GET['id'];

    $calendar = Calendar::getById($calendarId);

    $defaultReminders = Calendar::getReminders($calendarId);
    if ($calendar['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    if (isset($_POST['edit_calendar_settings'])) {
        $date = Util::getCurrentDateTime($session->get('client/settings/timezone'));
        Calendar::deleteReminders($calendarId);

        // reminder information
        foreach ($_POST as $key => $value) {
            if (strpos($key, 'reminder_type_') !== false) {
                $indexReminder = str_replace('reminder_type_', '', $key);
                $reminderType = Util::cleanRegularInputField($_POST[$key]);
                $reminderValue = $_POST['value_reminder_' . $indexReminder];
                $reminderPeriod = $_POST['reminder_period_' . $indexReminder];

                // add the reminder
                if (is_numeric($reminderValue)) {
                    Calendar::addReminder($calendarId, $reminderType, $reminderPeriod, $reminderValue);
                }
            }
        }

        Log::add($clientId, SystemProduct::SYS_PRODUCT_CALENDAR, $loggedInUserId, 'UPDATE Calendar Default Reminders ' . $calendar['name'], $date);

        header('Location: /calendar/calendars');
    }

    $menuSelectedCategory = 'calendar';
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_CALENDAR_NAME . ' / Calendar: ' . $calendar['name'] . ' / Settings';

    require_once __DIR__ . '/../Resources/views/Settings.php';