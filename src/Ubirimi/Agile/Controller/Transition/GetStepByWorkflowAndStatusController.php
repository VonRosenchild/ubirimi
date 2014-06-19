<?php
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Workflow\Workflow;

    Util::checkUserIsLoggedInAndRedirect();

    $workflowId = $_POST['workflow_id'];
    $StatusId = $_POST['status_id'];

    $step = Workflow::getStepByWorkflowIdAndStatusId($workflowId, $StatusId);

    echo json_encode($step);