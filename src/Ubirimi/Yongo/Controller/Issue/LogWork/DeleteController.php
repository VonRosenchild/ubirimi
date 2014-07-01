<?php
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\Issue;
    use Ubirimi\Yongo\Repository\Issue\IssueWorkLog;

    Util::checkUserIsLoggedInAndRedirect();
    $workLogId = $_POST['id'];
    $issueId = $_POST['issue_id'];
    $remainingTime = $_POST['remaining'];
    $comment = isset($_POST['comment']) ? Util::cleanRegularInputField($_POST['comment']) : null;

    $workLog = IssueWorkLog::getWorkLogById($workLogId);
    $timeSpent = $workLog['time_spent'];

    IssueWorkLog::deleteById($workLogId);

    $issueQueryParameters = array('issue_id' => $issueId);
    $issue = Issue::getByParameters($issueQueryParameters, $loggedInUserId);
    $previousEstimate = $issue['remaining_estimate'];

    if ($remainingTime == 'automatic')
        $remainingTime = '+' . $timeSpent;

    $remainingTime = IssueWorkLog::adjustRemainingEstimate($issue, $timeSpent, $remainingTime, $session->get('yongo/settings/time_tracking_hours_per_day'), $session->get('yongo/settings/time_tracking_days_per_week'), $loggedInUserId);

    $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));
    $fieldChanges = array(array('time_spent', $workLog['time_spent'], 0),
                          array('remaining_estimate', $previousEstimate, $remainingTime),
                          array('worklog_time_spent', $workLog['time_spent'], null));
    Issue::updateHistory($issue['id'], $loggedInUserId, $fieldChanges, $currentDate);

    echo $remainingTime;