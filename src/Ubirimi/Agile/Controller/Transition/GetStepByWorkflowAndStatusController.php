<?php

namespace Ubirimi\Agile\Controller\Transition;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Workflow\Workflow;

class GetStepByWorkflowAndStatusController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $workflowId = $request->request->get('workflow_id');
        $StatusId = $request->request->get('status_id');

        $step = $this->getRepository(Workflow::class)->getStepByWorkflowIdAndStatusId($workflowId, $StatusId);

        return new Response(json_encode($step));
    }
}
