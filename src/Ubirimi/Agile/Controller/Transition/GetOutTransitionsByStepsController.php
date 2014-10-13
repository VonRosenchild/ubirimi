<?php

namespace Ubirimi\Agile\Controller\Transition;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Workflow\Workflow;

class GetOutTransitionsByStepsController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $workflowId = $request->request->get('workflow_id');
        $stepIdFrom = $request->request->get('step_id_from');
        $stepIdTo = $request->request->get('step_id_to');
        $workflowData = $this->getRepository('yongo.workflow.workflow')->getDataByStepIdFromAndStepIdTo($workflowId, $stepIdFrom, $stepIdTo);

        return new Response(json_encode($workflowData));
    }
}
