<?php

namespace Ubirimi\HelpDesk\Controller\Queue;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;

class DeleteDialogController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        return new Response('<div>Are you sure you want to delete this queue?</div>');
    }
}
