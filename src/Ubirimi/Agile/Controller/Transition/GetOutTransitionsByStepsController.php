<?php
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Workflow\Workflow;

    Util::checkUserIsLoggedInAndRedirect();

    $workflowId = $_POST['workflow_id'];
    $stepIdFrom = $_POST['step_id_from'];
    $stepIdTo = $_POST['step_id_to'];
    $workflowData = Workflow::getDataByStepIdFromAndStepIdTo($workflowId, $stepIdFrom, $stepIdTo);

    echo json_encode($workflowData);