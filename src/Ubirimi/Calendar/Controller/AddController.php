<?php

namespace Ubirimi\Calendar\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Calendar\Repository\Calendar;
use Ubirimi\Calendar\Repository\CalendarEventReminderPeriod;
use Ubirimi\Calendar\Repository\CalendarReminderType;
use Ubirimi\Repository\Log;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class AddController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $menuSelectedCategory = 'calendars';

        $emptyName = false;
        $duplicateName = false;

        if ($request->request->has('confirm_new_calendar')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));
            $color = Util::cleanRegularInputField($request->request->get('color'));

            if (empty($name)) {
                $emptyName = true;
            }

            $calendarSameName = Calendar::getByName($session->get('user/id'), $name);
            if ($calendarSameName) {
                $duplicateName = true;
            }

            if (!$emptyName && !$duplicateName) {
                $currentDate = Util::getServerCurrentDateTime();
                $calendarId = Calendar::save($session->get('user/id'), $name, $description, $color, $currentDate);

                // add default reminders

                Calendar::addReminder(
                    $calendarId,
                    CalendarReminderType::REMINDER_EMAIL,
                    CalendarEventReminderPeriod::PERIOD_MINUTE,
                    30
                );

                Log::add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_CALENDAR,
                    $session->get('user/id'),
                    'ADD EVENTS calendar ' . $name,
                    $currentDate
                );

                return new RedirectResponse('/calendar/calendars');
            }
        }

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_CALENDAR_NAME . ' / Create Calendar';

        return $this->render(__DIR__ . '/../Resources/views/Add.php', get_defined_vars());
    }
}