<?php

namespace Ubirimi\Calendar\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Calendar\Repository\Calendar;
use Ubirimi\Repository\Log;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class SettingsController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $calendarId = $request->get('id');

        $calendar = Calendar::getById($calendarId);

        $defaultReminders = Calendar::getReminders($calendarId);
        if ($calendar['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        if ($request->request->has('edit_calendar_settings')) {
            $date = Util::getServerCurrentDateTime();
            Calendar::deleteReminders($calendarId);
            Calendar::deleteSharesByCalendarId($calendarId);

            // reminder information
            foreach ($request->request as $key => $value) {
                if (strpos($key, 'reminder_type_') !== false) {
                    $indexReminder = str_replace('reminder_type_', '', $key);
                    $reminderType = Util::cleanRegularInputField($request->request->get($key));
                    $reminderValue = $request->request->get('value_reminder_' . $indexReminder);
                    $reminderPeriod = $request->request->get('reminder_period_' . $indexReminder);

                    // add the reminder
                    if (is_numeric($reminderValue)) {
                        Calendar::addReminder($calendarId, $reminderType, $reminderPeriod, $reminderValue);
                    }
                }
            }

            $this->getRepository('ubirimi.general.log')->add(
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