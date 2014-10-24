<?php

namespace Ubirimi\Yongo\Controller\Administration\Workflow\Transition\PostFunction;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Workflow\WorkflowFunction;

class EditController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientId = $session->get('client/id');
        $loggedInUserId = $session->get('user/id');

        if ($request->request->has('edit_parameters')) {
            $functionId = $request->request->get('function_id');
            $workflowDataId = $request->request->get('workflow_data_id');

            switch ($functionId) {

                case WorkflowFunction::FUNCTION_SET_ISSUE_FIELD_VALUE:
                    $fieldCode = $request->request->get('issue_field');
                    $fieldValue = $request->request->get('field_value');
                    $definitionData = 'field_name=' . $fieldCode . '###field_value=' . $fieldValue;

                    $this->getRepository('yongo.workflow.workflowFunction')->updateByWorkflowDataIdAndFunctionId($workflowDataId, $functionId, $definitionData);

                    break;

                case WorkflowFunction::FUNCTION_FIRE_EVENT:

                    $event = $request->request->get('fire_event');
                    $definitionData = 'event=' . $event;

                    $this->getRepository('yongo.workflow.workflowFunction')->updateByWorkflowDataIdAndFunctionId($workflowDataId, $functionId, $definitionData);

                    break;
            }

            $currentDate = Util::getServerCurrentDateTime();
            $this->getRepository(UbirimiLog::class)->add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'UPDATE Yongo Workflow Post Function', $currentDate);

            return new RedirectResponse('/yongo/administration/workflow/transition-post-functions/' . $workflowDataId);
        }

        $workflowPostFunctionDataId = $request->get('id');

        $workflowPostFunctionData = $this->getRepository('yongo.workflow.workflowFunction')->getDataById($workflowPostFunctionDataId);

        $postFunctionId = $workflowPostFunctionData['function_id'];
        $definitionData = $workflowPostFunctionData['definition_data'];

        switch ($postFunctionId) {
            case WorkflowFunction::FUNCTION_SET_ISSUE_FIELD_VALUE:
                $data = explode("###", $definitionData);
                $fieldData = explode("=", $data[0]);
                $fieldName = $fieldData[1];
                $fieldValueData = explode('=', $data[1]);
                $fieldValue = $fieldValueData[1];
                break;
        }

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Post Function';
        $menuSelectedCategory = 'issue';
        return $this->render(__DIR__ . '/../../../../../Resources/views/administration/workflow/transition/post_function/EditData.php', get_defined_vars());
    }
}