<?php

namespace Ubirimi\Yongo\Controller\Administration\Workflow\Transition;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class DeleteAllController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $Id = $request->request->get('transition_id');
        $this->getRepository('yongo.workflow.workflow')->deleteDataById($Id);

        return new Response('');
    }
}
