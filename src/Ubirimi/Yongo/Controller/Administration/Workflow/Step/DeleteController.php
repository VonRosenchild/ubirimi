<?php

namespace Ubirimi\Yongo\Controller\Administration\Workflow\Step;

use Guzzle\Http\Message\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class DeleteController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $stepId = $request->request->get('id');

        $this->getRepository('yongo.workflow.workflow')->deleteStepById($stepId);

        return new Response('');
    }
}