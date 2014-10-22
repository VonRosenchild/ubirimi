<?php

namespace Ubirimi\Yongo\Controller\Administration\Workflow\Transition\PostFunction;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Workflow\WorkflowFunction;

class SaveController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $field_ids = $request->request->get('field_ids');
        $field_values = $request->request->get('field_values');
        $workflowId = $request->request->get('workflow_id');
        $IdFrom = $request->request->get('id_from');
        $IdTo = $request->request->get('id_to');
        $functionId = $field_values[0];

        $data = $this->getRepository('yongo.workflow.workflow')->getDataByStepIdFromAndStepIdTo($workflowId, $IdFrom, $IdTo);

        $value = '';

        if ($functionId == WorkflowFunction::FUNCTION_SET_ISSUE_FIELD_VALUE) {
            $field_name = $field_values[1];
            $field_value = $field_values[2];
            $value = 'field_name=' . $field_name . '###field_value=' . $field_value;
        }
        $this->getRepository('yongo.workflow.workflowFunction')->addPostFunction($data['id'], $functionId, $value);
    }
}