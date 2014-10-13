<?php

namespace Ubirimi\Calendar\Controller\Event;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\User\User;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class AddGuestConfirmController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $eventId = $request->get('id');
        $users = $this->getRepository('ubirimi.user.user')->getByClientId($session->get('client/id'));

        return $this->render(__DIR__ . '/../../Resources/views/event/AddGuestConfirm.php', get_defined_vars());
    }
}