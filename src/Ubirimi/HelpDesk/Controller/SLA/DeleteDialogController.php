<?php

namespace Ubirimi\HelpDesk\Controller\SLA;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;

class DeleteDialogController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        return new Response(
            '<div>Are you sure you want to delete this SLA?</div>' .
            '<div>It will be removed from all associated issues in the project</div>'
        );
    }
}

