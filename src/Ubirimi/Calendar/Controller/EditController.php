<?php

namespace Ubirimi\Calendar\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use Ubirimi\Calendar\Repository\Calendar\Calendar;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class EditController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $calendarId = $request->get('id');
        $calendar = $this->getRepository('calendar.calendar.calendar')->getById($calendarId);

        if ($calendar['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $emptyName = false;
        $calendarExists = false;

        if ($request->request->has('edit_calendar')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));
            $color = Util::cleanRegularInputField($request->request->get('color'));

            if (empty($name)) {
                $emptyName = true;
            }

            // check for duplication
            $calendarDuplicate = $this->getRepository('calendar.calendar.calendar')->getByName($session->get('user/id'), mb_strtolower($name), $calendarId);
            if ($calendarDuplicate) {
                $calendarExists = true;
            }
            if (!$calendarExists && !$emptyName) {
                $date = Util::getServerCurrentDateTime();
                $this->getRepository('calendar.calendar.calendar')->updateById($calendarId, $name, $description, $color, $date);

                $this->getRepository('ubirimi.general.log')->add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_CALENDAR,
                    $session->get('user/id'),
                    'UPDATE EVENTS calendar ' . $name,
                    $date
                );

                return new RedirectResponse('/calendar/calendars');
            }
        }

        $menuSelectedCategory = 'calendar';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_CALENDAR_NAME . ' / Calendar: ' . $calendar['name'] . ' / Update';

        return $this->render(__DIR__ . '/../Resources/views/Edit.php', get_defined_vars());
    }
}