<?php

namespace Ubirimi\HelpDesk\Controller\SLA\Calendar;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\HelpDesk\Repository\Sla\Calendar;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\SystemProduct;

class EditController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $clientSettings = $session->get('client/settings');

        $menuSelectedCategory = 'help_desk';
        $menuProjectCategory = 'sla_calendar';
        $sectionPageTitle = $clientSettings['title_name']
            . ' / ' . SystemProduct::SYS_PRODUCT_HELP_DESK_NAME
            . ' / Help Desks > Edit Calendar';

        $emptyName = false;
        $duplicateName = false;

        $calendarId = $request->get('id');
        $calendar = Calendar::getById($calendarId);
        $projectId = $calendar['project_id'];
        $data = Calendar::getData($calendarId);

        if ($request->request->has('confirm_edit_calendar')) {

            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));

            if (empty($name)) {
                $emptyName = true;
            }

            $existingCalendar = Calendar::getByName($name, $projectId, $calendarId);
            if ($existingCalendar) {
                $duplicateName = true;
            }
            if (!$emptyName && !$duplicateName) {
                $dataCalendar = array();
                for ($i = 1; $i <= 7; $i++) {
                    $dataCalendar[$i - 1]['from_hour'] = $request->request->get('from_' . $i . '_hour');
                    $dataCalendar[$i - 1]['from_minute'] = $request->request->get('from_' . $i . '_minute');
                    $dataCalendar[$i - 1]['to_hour'] = $request->request->get('to_' . $i . '_hour');
                    $dataCalendar[$i - 1]['to_minute'] = $request->request->get('to_' . $i . '_minute');
                    $dataCalendar[$i - 1]['notWorking'] = $request->request->get('not_working_day_' . $i, 0);
                }

                if (!$emptyName && !$duplicateName) {

                    $currentDate = Util::getServerCurrentDateTime();

                    Calendar::deleteDataByCalendarId($calendarId);
                    Calendar::updateById($calendarId, null, $name, $description, $currentDate);
                    Calendar::addData($calendarId, $dataCalendar);

                    return new RedirectResponse('/helpdesk/sla/calendar/' . $projectId);
                }
            }
        }

        return $this->render(__DIR__ . '/../../../Resources/views/sla/calendar/Edit.php', get_defined_vars());
    }
}
