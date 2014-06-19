<?php
    use Ubirimi\Calendar\Repository\Calendar;
    use Ubirimi\Calendar\Repository\CalendarEventReminderPeriod;
    use Ubirimi\Calendar\Repository\CalendarReminderType;
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $menuSelectedCategory = 'calendars';

    $emptyName = false;
    $duplicateName = false;
    if (isset($_POST['confirm_new_calendar'])) {
        $name = Util::cleanRegularInputField($_POST['name']);
        $description = Util::cleanRegularInputField($_POST['description']);
        $color = Util::cleanRegularInputField($_POST['color']);

        if (empty($name)) {
            $emptyName = true;
        }

        $calendarSameName = Calendar::getByName($loggedInUserId, $name);
        if ($calendarSameName) {
            $duplicateName = true;
        }

        if (!$emptyName && !$duplicateName) {
            $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));
            $calendarId = Calendar::save($loggedInUserId, $name, $description, $color, $currentDate);

            // add default reminders

            Calendar::addReminder($calendarId, CalendarReminderType::REMINDER_EMAIL, CalendarEventReminderPeriod::PERIOD_MINUTE, 30);

            Log::add($clientId, SystemProduct::SYS_PRODUCT_CALENDAR, $loggedInUserId, 'ADD EVENTS calendar ' . $name, $currentDate);

            header('Location: /calendar/calendars');
        }
    }

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_CALENDAR_NAME . ' / Create Calendar';

    require_once __DIR__ . '/../Resources/views/Add.php';