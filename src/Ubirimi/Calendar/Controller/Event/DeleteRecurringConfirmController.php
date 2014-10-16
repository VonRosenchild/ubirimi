<?php

namespace Ubirimi\Calendar\Controller\Event;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use Ubirimi\UbirimiController;
use Ubirimi\Util;

class DeleteRecurringConfirmController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $eventId = $_GET['id'];

        return $this->render(__DIR__ . '/../../Resources/views/event/DeleteRecurringConfirm.php', get_defined_vars());
    }
}