<?php

namespace Ubirimi\HelpDesk\Controller\SLA\Calendar;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\HelpDesk\Sla;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class DeleteDialogController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $calendarId = $request->get('id');
        $slasWithCalendar = Sla::getByCalendarId($session->get('client/id'), $calendarId);

        $slas = array();
        if ($slasWithCalendar) {
            while ($sla = $slasWithCalendar->fetch_array(MYSQLI_ASSOC)) {
                $slas[] = $sla['name'];
            }
        }

        return $this->render(__DIR__ . '/../../../Resources/views/sla/calendar/DeleteDialog.php', get_defined_vars());
    }
}