<?php

namespace Ubirimi\Calendar\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Calendar\Repository\Calendar;
use Ubirimi\Calendar\Repository\Calendar\UbirimiCalendar;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class SettingsController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $calendarId = $request->get('id');

        $calendar = $this->getRepository(UbirimiCalendar::class)->getById($calendarId);

        $defaultReminders = $this->getRepository(UbirimiCalendar::class)->getReminders($calendarId);
        if ($calendar['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        if ($request->request->has('edit_calendar_settings')) {
            $date = Util::getServerCurrentDateTime();
            $this->getRepository(UbirimiCalendar::class)->deleteReminders($calendarId);
            $this->getRepository(UbirimiCalendar::class)->deleteSharesByCalendarId($calendarId);

            // reminder information
            foreach ($request->request as $key => $value) {
                if (strpos($key, 'reminder_type_') !== false) {
                    $indexReminder = str_replace('reminder_type_', '', $key);
                    $reminderType = Util::cleanRegularInputField($request->request->get($key));
                    $reminderValue = $request->request->get('value_reminder_' . $indexReminder);
                    $reminderPeriod = $request->request->get('reminder_period_' . $indexReminder);

                    // add the reminder
                    if (is_numeric($reminderValue)) {
                        $this->getRepository(UbirimiCalendar::class)->addReminder($calendarId, $reminderType, $reminderPeriod, $reminderValue);
                    }
                }
            }

            $this->getRepository(UbirimiLog::class)->add(
                $session->get('client/id'),
                SystemProduct::SYS_PRODUCT_CALENDAR,
                $session->get('user/id'),
                'UPDATE Calendar Default Reminders ' . $calendar['name'],
                $date
            );

            return new RedirectResponse('/calendar/calendars');
        }

        $menuSelectedCategory = 'calendar';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_CALENDAR_NAME . ' / Calendar: ' . $calendar['name'] . ' / Settings';

        return $this->render(__DIR__ . '/../Resources/views/Settings.php', get_defined_vars());
    }
}