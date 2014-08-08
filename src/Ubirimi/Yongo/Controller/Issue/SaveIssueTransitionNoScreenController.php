<?php
    use Ubirimi\Repository\Client;
    use Ubirimi\Repository\Email\Email;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\Issue;
    use Ubirimi\Yongo\Repository\Workflow\Workflow;
    use Ubirimi\Yongo\Repository\Workflow\WorkflowFunction;

    Util::checkUserIsLoggedInAndRedirect();

    $workflowStepIdFrom = $_POST['step_id_from'];
    $workflowStepIdTo = $_POST['step_id_to'];
    $workflowId = $_POST['workflow_id'];
    $issueId = $_POST['issue_id'];

    $clientSettings = Client::getSettings($clientId);

    $workflowData = Workflow::getDataByStepIdFromAndStepIdTo($workflowId, $workflowStepIdFrom, $workflowStepIdTo);
    $issue = Issue::getByParameters(array('issue_id' => $issueId), $loggedInUserId);

    $canBeExecuted = Workflow::checkConditionsByTransitionId($workflowData['id'], $loggedInUserId, $issue);

    if ($canBeExecuted) {

        $smtpSettings = $session->get('client/settings/smtp');
        if ($smtpSettings) {
            Email::$smtpSettings = $smtpSettings;
        }

        $date = Util::getServerCurrentDateTime();
        WorkflowFunction::triggerPostFunctions($clientId, $issue, $workflowData, array(), $loggedInUserId, $date);

        // update the date_updated field
        Issue::updateById($issueId, array('date_updated' => $date), $date);

        echo 'success';

    } else {
        echo 'can_not_be_executed';
    }