<?php

namespace Ubirimi\Yongo\Controller\Administration\Workflow\Transition\Condition;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;

class DeleteAllDialogController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        return new Response('Are you sure you want to delete all the conditions associated with this transition?');
    }
}