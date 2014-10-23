<?php

namespace Ubirimi\Calendar\Controller\Event;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Calendar\Repository\Reminder\Reminder;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class DeleteReminderController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $reminderId = $request->request->get('id');

        Reminder::deleteById($reminderId);

        return new Response('');
    }
}
