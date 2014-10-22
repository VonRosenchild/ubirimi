<?php

namespace Ubirimi\Yongo\Controller\Administration\Workflow;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Workflow\Position;

class GetController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $workflowId = $request->request->get('id');
        $workflowData = $this->getRepository('yongo.workflow.workflow')->getDataByWorkflowId($workflowId);

        $result = array();
        if ($workflowData) {
            while ($workflow = $workflowData->fetch_array(MYSQLI_ASSOC)) {
                $result[] = $workflow;
            }
        }

        $positions = array();

        $position_result = Position::getByWorkflowId($workflowId);
        if ($position_result) {
            while ($position = $position_result->fetch_array(MYSQLI_ASSOC)) {
                $positions[] = $position;
            }
        }

        $finalResult = array('values' => $result, 'positions' => $positions);

        return new Response(json_encode($finalResult));
    }
}