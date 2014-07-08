<?php

namespace Ubirimi\Yongo\Controller\Issue\LogWork;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\IssueWorkLog;
use Ubirimi\Yongo\Repository\Issue\Issue;

class DeleteController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $workLogId = $request->request->get('id');
        $issueId = $request->request->get('issue_id');
        $remainingTime = $request->request->get('remaining');
        $comment = Util::cleanRegularInputField($request->request->get('comment'));

        $workLog = IssueWorkLog::getWorkLogById($workLogId);
        $timeSpent = $workLog['time_spent'];

        IssueWorkLog::deleteById($workLogId);

        $issueQueryParameters = array('issue_id' => $issueId);
        $issue = Issue::getByParameters($issueQueryParameters, $session->get('user/id'));
        $previousEstimate = $issue['remaining_estimate'];

        if ($remainingTime == 'automatic')
            $remainingTime = '+' . $timeSpent;

        $remainingTime = IssueWorkLog::adjustRemainingEstimate(
            $issue,
            $timeSpent,
            $remainingTime,
            $session->get('yongo/settings/time_tracking_hours_per_day'),
            $session->get('yongo/settings/time_tracking_days_per_week'),
            $session->get('user/id')
        );

        $currentDate = Util::getServerCurrentDateTime();

        $fieldChanges = array(
            array('time_spent', $workLog['time_spent'], 0),
            array('remaining_estimate', $previousEstimate, $remainingTime),
            array('worklog_time_spent', $workLog['time_spent'], null));

        Issue::updateHistory($issue['id'], $session->get('user/id'), $fieldChanges, $currentDate);

        return new Response($remainingTime);
    }
}
