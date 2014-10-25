<?php

namespace Ubirimi\Yongo\Controller\Administration\Workflow\Transition\Condition;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Workflow\Workflow;
use Ubirimi\Yongo\Repository\Workflow\WorkflowCondition;

class AddDataController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientId = $session->get('client/id');
        $loggedInUserId = $session->get('user/id');

        $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_YONGO);

        $workflowDataId = $request->get('id');
        $workflowData = $this->getRepository(Workflow::class)->getDataById($workflowDataId);
        $workflow = $this->getRepository(Workflow::class)->getMetaDataById($workflowData['workflow_id']);

        $conditionId = $request->request->get('condition');

        $currentDate = Util::getServerCurrentDateTime();

        if ($conditionId) {

            $conditionData = $this->getRepository(WorkflowCondition::class)->getByTransitionId($workflowDataId);
            if (!$conditionData) {
                $this->getRepository(Workflow::class)->addCondition($workflowDataId, '');
            }
            if ($conditionId == WorkflowCondition::CONDITION_ONLY_ASSIGNEE) {

                $definitionData = 'cond_id=' . WorkflowCondition::CONDITION_ONLY_ASSIGNEE;
                $this->getRepository(WorkflowCondition::class)->addConditionString($workflowDataId, $definitionData);

                $this->getRepository(UbirimiLog::class)->add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'ADD Yongo Workflow Condition' , $currentDate);

                return new RedirectResponse('/yongo/administration/workflow/transition-conditions/' . $workflowDataId);
            } else
                if ($conditionId == WorkflowCondition::CONDITION_ONLY_REPORTER) {

                    $definitionData = 'cond_id=' . WorkflowCondition::CONDITION_ONLY_REPORTER;

                    $this->getRepository(WorkflowCondition::class)->addConditionString($workflowDataId, $definitionData);

                    $this->getRepository(UbirimiLog::class)->add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'ADD Yongo Workflow Condition' , $currentDate);

                    return new RedirectResponse('/yongo/administration/workflow/transition-conditions/' . $workflowDataId);
                }
        } else {

        }

        if ($request->request->has('confirm_new_condition_parameter')) {
            $conditionId = $request->get('condition_id');
            if ($conditionId == WorkflowCondition::CONDITION_PERMISSION) {

                $permissionId = $request->request->get('permission');

                $conditionString = 'perm_id=' . $permissionId;

                $this->getRepository(WorkflowCondition::class)->addConditionString($workflowDataId, $conditionString);

                $this->getRepository(UbirimiLog::class)->add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'ADD Yongo Workflow Condition' , $currentDate);

                return new RedirectResponse('/yongo/administration/workflow/transition-conditions/' . $workflowDataId);
            }
        }

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Workflow / Add Condition Data';

        return $this->render(__DIR__ . '/../../../../../Resources/views/administration/workflow/transition/condition/AddData.php', get_defined_vars());
    }
}