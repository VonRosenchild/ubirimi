<?php

namespace Ubirimi\Yongo\Controller\Administration\Workflow\Transition\PostFunction;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Workflow\WorkflowFunction;

class AddDataController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $clientId = $session->get('client/id');
        $loggedInUserId = $session->get('user/id');

        $workflowDataId = $request->get('id');
        $postFunctionId = $request->get('function_id');
        $postFunctionSelected = $this->getRepository('yongo.workflow.workflowFunction')->getById($postFunctionId);
        $workflowData = $this->getRepository('yongo.workflow.workflow')->getDataById($workflowDataId);
        $workflow = $this->getRepository('yongo.workflow.workflow')->getMetaDataById($workflowData['workflow_id']);

        $postFunctions = $this->getRepository('yongo.workflow.workflowFunction')->getAll();

        if (isset($_POST['add_parameters'])) {

            if ($postFunctionId == WorkflowFunction::FUNCTION_SET_ISSUE_FIELD_VALUE) {
                $field_name = $request->request->get('issue_field');
                $field_value = $request->request->get('field_value');
                $value = 'field_name=' . $field_name . '###field_value=' . $field_value;

                $this->getRepository('yongo.workflow.workflowFunction')->addPostFunction($workflowDataId, $postFunctionId, $value);

                $currentDate = Util::getServerCurrentDateTime();
                $this->getRepository('ubirimi.general.log')->add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'ADD Yongo Workflow Post Function', $currentDate);
            }

            header('Location: /yongo/administration/workflow/transition-post-functions/' . $workflowDataId);
        }

        $menuSelectedCategory = 'issue';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Post Function Data';
        require_once __DIR__ . '/../../../../../Resources/views/administration/workflow/transition/post_function/AddData.php';
    }
}