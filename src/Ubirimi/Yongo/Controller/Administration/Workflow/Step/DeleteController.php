<?php

namespace Ubirimi\Yongo\Controller\Administration\Workflow\Step;

use Guzzle\Http\Message\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Workflow\Workflow;

class DeleteController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $stepId = $request->request->get('id');

        $this->getRepository(Workflow::class)->deleteStepById($stepId);

        return new Response('');
    }
}