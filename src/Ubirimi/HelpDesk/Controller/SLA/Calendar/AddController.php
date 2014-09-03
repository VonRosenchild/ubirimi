<?php

namespace Ubirimi\HelpDesk\Controller\SLA\Calendar;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\SystemProduct;
use Ubirimi\Repository\HelpDesk\SLACalendar;

class AddController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $clientSettings = $session->get('client/settings');

        $menuSelectedCategory = 'help_desk';
        $menuProjectCategory = 'sla_calendar';
        $sectionPageTitle = $clientSettings['title_name']
            . ' / ' . SystemProduct::SYS_PRODUCT_HELP_DESK_NAME
            . ' / Help Desks > Create Calendar';

        $emptyName = false;
        $duplicateName = false;

        $projectId = $request->get('id');

        if ($request->request->has('confirm_new_calendar')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));

            if (empty($name)) {
                $emptyName = true;
            }

            $slaCalendarExisting = SLACalendar::getByName($name, $projectId);
            if ($slaCalendarExisting) {
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

                    SLACalendar::addCalendar($projectId, $name, $description, $dataCalendar, 0, $currentDate);

                    return new RedirectResponse('/helpdesk/sla/calendar/' . $projectId);
                }
            }
        }

        return $this->render(__DIR__ . '/../../../Resources/views/sla/calendar/Add.php', get_defined_vars());
    }
}
