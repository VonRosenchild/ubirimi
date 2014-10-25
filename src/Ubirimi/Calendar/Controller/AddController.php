<?php

namespace Ubirimi\Calendar\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Calendar\Repository\Calendar\UbirimiCalendar;
use Ubirimi\Calendar\Repository\Reminder\ReminderPeriod;
use Ubirimi\Calendar\Repository\Reminder\ReminderType;
use Ubirimi\Repository\General\UbirimiLog;
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

            $calendarSameName = $this->getRepository(UbirimiCalendar::class)->getByName($session->get('user/id'), $name);
            if ($calendarSameName) {
                $duplicateName = true;
            }

            if (!$emptyName && !$duplicateName) {
                $currentDate = Util::getServerCurrentDateTime();
                $calendarId = $this->getRepository(UbirimiCalendar::class)->save($session->get('user/id'), $name, $description, $color, $currentDate);

                // add default reminders

                $this->getRepository(UbirimiCalendar::class)->addReminder(
                    $calendarId,
                    ReminderType::REMINDER_EMAIL,
                    ReminderPeriod::PERIOD_MINUTE,
                    30
                );

                $this->getRepository(UbirimiLog::class)->add(
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