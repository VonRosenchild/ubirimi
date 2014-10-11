<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Workflow\Workflow;
    use Ubirimi\Yongo\Repository\Workflow\Condition;

    Util::checkUserIsLoggedInAndRedirect();

    $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_YONGO);

    $workflowDataId = $_GET['id'];
    $workflowData = Workflow::getDataById($workflowDataId);
    $workflow = Workflow::getMetaDataById($workflowData['workflow_id']);

    $conditionId = isset($_POST['condition']) ? $_POST['condition'] : null;

    $currentDate = Util::getServerCurrentDateTime();

    if ($conditionId) {

        $conditionData = Condition::getByTransitionId($workflowDataId);
        if (!$conditionData) {
            Workflow::addCondition($workflowDataId, '');
        }
        if ($conditionId == Condition::CONDITION_ONLY_ASSIGNEE) {

            $definitionData = 'cond_id=' . Condition::CONDITION_ONLY_ASSIGNEE;
            Condition::addConditionString($workflowDataId, $definitionData);

            Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'ADD Yongo Workflow Condition' , $currentDate);

            header('Location: /yongo/administration/workflow/transition-conditions/' . $workflowDataId);
        } else
            if ($conditionId == Condition::CONDITION_ONLY_REPORTER) {

                $definitionData = 'cond_id=' . Condition::CONDITION_ONLY_REPORTER;

            Condition::addConditionString($workflowDataId, $definitionData);

            Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'ADD Yongo Workflow Condition' , $currentDate);

            header('Location: /yongo/administration/workflow/transition-conditions/' . $workflowDataId);
        }
    } else {

    }

    if (isset($_POST['confirm_new_condition_parameter'])) {
        $conditionId = $_GET['condition_id'];
        if ($conditionId == Condition::CONDITION_PERMISSION) {

            $permissionId = $_POST['permission'];

            $conditionString = 'perm_id=' . $permissionId;

            Condition::addConditionString($workflowDataId, $conditionString);

            Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'ADD Yongo Workflow Condition' , $currentDate);

            header('Location: /yongo/administration/workflow/transition-conditions/' . $workflowDataId);
        }
    }

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Workflow / Add Condition Data';

    require_once __DIR__ . '/../../../../../Resources/views/administration/workflow/transition/condition/AddData.php';