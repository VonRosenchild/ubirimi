<?php
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Workflow\Workflow;
    use Ubirimi\Yongo\Repository\Workflow\WorkflowPosition;

    Util::checkUserIsLoggedInAndRedirect();

    $workflowId = $_POST['id'];
    $workflowData = Workflow::getDataByWorkflowId($workflowId);

    $result = array();
    if ($workflowData) {
        while ($workflow = $workflowData->fetch_array(MYSQLI_ASSOC)) {
            $result[] = $workflow;
        }
    }

    $positions = array();

    $position_result = WorkflowPosition::getByWorkflowId($workflowId);
    if ($position_result) {
        while ($position = $position_result->fetch_array(MYSQLI_ASSOC)) {
            $positions[] = $position;
        }
    }

    $finalResult = array('values' => $result, 'positions' => $positions);

    echo json_encode($finalResult);