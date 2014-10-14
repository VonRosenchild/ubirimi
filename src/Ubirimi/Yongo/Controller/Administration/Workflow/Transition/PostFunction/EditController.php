<?php

    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Workflow\WorkflowFunction;

    Util::checkUserIsLoggedInAndRedirect();

    if (isset($_POST['edit_parameters'])) {
        $functionId = $_POST['function_id'];
        $workflowDataId = $_POST['workflow_data_id'];

        switch ($functionId) {

            case WorkflowFunction::FUNCTION_SET_ISSUE_FIELD_VALUE:
                $fieldCode = $_POST['issue_field'];
                $fieldValue = $_POST['field_value'];
                $definitionData = 'field_name=' . $fieldCode . '###field_value=' . $fieldValue;

                WorkflowFunction::updateByWorkflowDataIdAndFunctionId($workflowDataId, $functionId, $definitionData);

                break;

            case WorkflowFunction::FUNCTION_FIRE_EVENT:

                $event = $_POST['fire_event'];
                $definitionData = 'event=' . $event;

                WorkflowFunction::updateByWorkflowDataIdAndFunctionId($workflowDataId, $functionId, $definitionData);

            break;
        }

        $currentDate = Util::getServerCurrentDateTime();
        $this->getRepository('ubirimi.general.log')->add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'UPDATE Yongo Workflow Post Function', $currentDate);

        header('Location: /yongo/administration/workflow/transition-post-functions/' . $workflowDataId);
    }

    $workflowPostFunctionDataId = $_GET['id'];

    $workflowPostFunctionData = WorkflowFunction::getDataById($workflowPostFunctionDataId);

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

    require_once __DIR__ . '/../../../../../Resources/views/administration/workflow/transition/post_function/EditData.php';