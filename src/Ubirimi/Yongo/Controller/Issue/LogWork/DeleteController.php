<?php

namespace Ubirimi\Yongo\Controller\Issue\LogWork;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Issue\WorkLog;

class DeleteController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $workLogId = $request->request->get('id');
        $issueId = $request->request->get('issue_id');
        $remainingTime = $request->request->get('remaining');
        $comment = Util::cleanRegularInputField($request->request->get('comment'));

        $workLog = $this->getRepository(WorkLog::class)->getById($workLogId);
        $timeSpent = $workLog['time_spent'];

        $this->getRepository(WorkLog::class)->deleteById($workLogId);

        $issueQueryParameters = array('issue_id' => $issueId);
        $issue = $this->getRepository(Issue::class)->getByParameters($issueQueryParameters, $session->get('user/id'));
        $previousEstimate = $issue['remaining_estimate'];

        if ($remainingTime == 'automatic')
            $remainingTime = '+' . $timeSpent;

        $remainingTime = $this->getRepository(WorkLog::class)->adjustRemainingEstimate(
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

        $this->getRepository(Issue::class)->updateHistory($issue['id'], $session->get('user/id'), $fieldChanges, $currentDate);

        // update the date_updated field
        $this->getRepository(Issue::class)->updateById($issueId, array('date_updated' => $currentDate), $currentDate);

        return new Response($remainingTime);
    }
}
