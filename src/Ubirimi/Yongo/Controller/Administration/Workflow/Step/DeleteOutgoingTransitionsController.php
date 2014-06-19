<?php
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Workflow\Workflow;

    Util::checkUserIsLoggedInAndRedirect();

    $workflowId = $_POST['workflow_id'];
    $stepId = $_POST['step_id'];
    Workflow::deleteOutgoingTransitionsForStepId($workflowId, $stepId);