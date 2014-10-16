<?php

namespace Ubirimi\Calendar\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Calendar\Repository\Calendar;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class DeleteReminder extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $reminderId = $request->request->get('id');

        $this->getRepository('calendar.calendar.calendar')->deleteReminderById($reminderId);

        return new Response('');
    }
}
