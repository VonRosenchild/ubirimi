<?php

namespace Ubirimi\Yongo\Controller\Administration\Workflow\Transition;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Workflow\Workflow;

class UpdateController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $transition_name = $request->request->get('transition_name');
        $workflowId = $request->request->get('workflow_id');
        $screenId = $request->request->get('screen_id');
        $idFrom = $request->request->get('id_from');
        $idTo = $request->request->get('id_to');

        $this->getRepository(Workflow::class)->updateTransitionData($workflowId, $transition_name, $screenId, $idFrom, $idTo);

        return new Response('');
    }
}