<?php

namespace Ubirimi\Calendar\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Calendar\Repository\Calendar;
use Ubirimi\Calendar\Repository\Calendar\UbirimiCalendar;
use Ubirimi\UbirimiController;

class DeleteConfirmController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $calendarId = $request->get('id');
        $calendar = $this->getRepository(UbirimiCalendar::class)->getById($calendarId);
        $defaultCalendar = false;
        if (1 == $calendar['default_flag']) {
            $defaultCalendar = true;
        }

        return $this->render(__DIR__ . '/../Resources/views/DeleteConfirm.php', get_defined_vars());
    }
}