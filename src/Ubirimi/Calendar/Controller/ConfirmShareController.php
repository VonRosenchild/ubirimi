<?php

namespace Ubirimi\Calendar\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Calendar\Repository\Calendar;
use Ubirimi\Repository\User\User;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class ConfirmShareController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $calendarId = $request->get('id');
        $users = User::getByClientId($session->get('client/id'));
        $sharedWithUsers = Calendar::getSharedUsers($calendarId, 'array');
        return $this->render(__DIR__ . '/../Resources/views/ConfirmShare.php', get_defined_vars());
    }
}