<?php

namespace Ubirimi\Agile\Controller\Transition;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Workflow\Workflow;

class GetOutTransitionsController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $workflowId = $request->request->get('workflow_id');
        $stepIdFrom = $request->request->get('step_id_from');

        $issueId = $request->request->get('issue_id');
        $projectId = $request->request->get('project_id');

        $issueQueryParameters = array('issue_id' => $issueId);
        $issue = UbirimiContainer::getRepository('yongo.issue.issue')->getByParameters($issueQueryParameters, $session->get('user/id'));

        $transitions = $this->getRepository('yongo.workflow.workflow')->getOutgoingTransitionsForStep($workflowId, $stepIdFrom, 'array');

        // for each transition determine if the conditions allow it to be executed
        $transitionsToBeExecuted = array();
        for ($i = 0; $i < count($transitions); $i++) {
            $canBeExecuted = $this->getRepository('yongo.workflow.workflow')->checkConditionsByTransitionId(
                $transitions[$i]['id'],
                $session->get('user/id'),
                $issue
            );

            if ($canBeExecuted)
                $transitionsToBeExecuted[] = $transitions[$i];
        }

        return new Response(json_encode($transitionsToBeExecuted));
    }
}
