<?php

namespace Ubirimi\Yongo\Controller\Administration\Workflow\Step\Transition;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class DeleteOutgoingController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $workflowId = $request->request->get('workflow_id');
        $stepId = $request->request->get('step_id');
        $this->getRepository('yongo.workflow.workflow')->deleteOutgoingTransitionsForStepId($workflowId, $stepId);

        return new Response('');
    }
}
