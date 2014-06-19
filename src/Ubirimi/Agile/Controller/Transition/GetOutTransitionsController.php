<?php
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\Issue;
    use Ubirimi\Yongo\Repository\Workflow\Workflow;

    Util::checkUserIsLoggedInAndRedirect();

    $workflowId = $_POST['workflow_id'];
    $stepIdFrom = $_POST['step_id_from'];

    $issueId = $_POST['issue_id'];
    $projectId = $_POST['project_id'];

    $issueQueryParameters = array('issue_id' => $issueId);
    $issue = Issue::getByParameters($issueQueryParameters, $loggedInUserId);

    $transitions = Workflow::getOutgoingTransitionsForStep($workflowId, $stepIdFrom, 'array');

    // for each transition determine if the conditions allow it to be executed
    $transitionsToBeExecuted = array();
    for ($i = 0; $i < count($transitions); $i++) {
        $canBeExecuted = Workflow::checkConditionsByTransitionId($transitions[$i]['id'], $loggedInUserId, $issue);
        if ($canBeExecuted)
            $transitionsToBeExecuted[] = $transitions[$i];
    }

    echo json_encode($transitionsToBeExecuted);