<?php
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Workflow\Workflow;
    use Ubirimi\Yongo\Repository\Workflow\WorkflowFunction;

    Util::checkUserIsLoggedInAndRedirect();

    $field_ids = $_POST['field_ids'];
    $field_values = $_POST['field_values'];
    $workflowId = $_POST['workflow_id'];
    $IdFrom = $_POST['id_from'];
    $IdTo = $_POST['id_to'];
    $functionId = $field_values[0];

    $data = Workflow::getDataByStepIdFromAndStepIdTo($workflowId, $IdFrom, $IdTo);

    $value = '';

    if ($functionId == WorkflowFunction::FUNCTION_SET_ISSUE_FIELD_VALUE) {
        $field_name = $field_values[1];
        $field_value = $field_values[2];
        $value = 'field_name=' . $field_name . '###field_value=' . $field_value;
    }
    WorkflowFunction::addPostFunction($data['id'], $functionId, $value);