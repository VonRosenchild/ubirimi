<?php
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\Issue;
    use Ubirimi\Yongo\Repository\Issue\IssueWorkLog;

    Util::checkUserIsLoggedInAndRedirect();
    $workLogId = $_POST['id'];
    $issueId = $_POST['issue_id'];
    $timeSpent = trim(str_replace(" ", '', $_POST['time_spent']));
    $dateStartedString = $_POST['date_started'];
    $remainingTimePost = $_POST['remaining'];
    $comment = $_POST['comment'];

    $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));
    $dateStarted = DateTime::createFromFormat('d-m-Y H:i', $dateStartedString);
    $dateStartedString = date_format($dateStarted, 'Y-m-d H:i');

    $workLog = IssueWorkLog::getWorkLogById($workLogId);

    IssueWorkLog::updateLogById($workLogId, $timeSpent, $dateStartedString, $comment);

    $issueQueryParameters = array('issue_id' => $issueId);
    $issue = Issue::getByParameters($issueQueryParameters, $loggedInUserId);

    $remaining = IssueWorkLog::adjustRemainingEstimate($issue, null, "+" . $workLog['time_spent'], $session->get('yongo/settings/time_tracking_hours_per_day'), $session->get('yongo/settings/time_tracking_days_per_week'), $loggedInUserId);

    $previousIssueRemainingEstimate = $issue['remaining_estimate'];

    $issue['remaining_estimate'] = $remaining;

    $remainingTimePost = IssueWorkLog::adjustRemainingEstimate($issue, $timeSpent, $remainingTimePost, $session->get('yongo/settings/time_tracking_hours_per_day'), $session->get('yongo/settings/time_tracking_days_per_week'), $loggedInUserId);

    // update the history
    $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));
    $fieldChanges = array(array('time_spent', $workLog['time_spent'], $timeSpent),
                          array('remaining_estimate', $previousIssueRemainingEstimate, $remainingTimePost));

    Issue::updateHistory($issue['id'], $loggedInUserId, $fieldChanges, $currentDate);
    echo $remainingTimePost;