<?php

namespace Ubirimi\Calendar\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Calendar\Repository\Calendar;
use Ubirimi\Calendar\Repository\Calendar\UbirimiCalendar;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class DeleteReminder extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $reminderId = $request->request->get('id');

        $this->getRepository(UbirimiCalendar::class)->deleteReminderById($reminderId);

        return new Response('');
    }
}
