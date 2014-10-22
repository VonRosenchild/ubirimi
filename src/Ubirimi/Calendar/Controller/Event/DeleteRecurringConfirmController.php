<?php

namespace Ubirimi\Calendar\Controller\Event;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;

class DeleteRecurringConfirmController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $eventId = $request->get('id');

        return $this->render(__DIR__ . '/../../Resources/views/event/DeleteRecurringConfirm.php', get_defined_vars());
    }
}