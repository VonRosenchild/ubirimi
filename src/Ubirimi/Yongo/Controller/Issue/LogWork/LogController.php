<?php

namespace Ubirimi\Yongo\Controller\Issue\LogWork;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Issue\WorkLog;

class LogController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $loggedInUserId = $session->get('user/id');

        $issueId = $request->request->get('id');
        $timeSpentPost = trim(str_replace(" ", '', $request->request->get('time_spent')));
        $dateStartedString = $request->request->get('date_started');
        $remainingTime = $request->request->get('remaining');
        $comment = $request->request->get('comment');

        $dateStarted = \DateTime::createFromFormat('d-m-Y H:i', $dateStartedString);
        $dateStartedString = date_format($dateStarted, 'Y-m-d H:i');

        if (is_numeric($timeSpentPost)) {
            $timeSpentPost = $timeSpentPost . $session->get('yongo/settings/time_tracking_default_unit');
        }

        if ($timeSpentPost) {
            $currentDate = Util::getServerCurrentDateTime();

            $issueQueryParameters = array('issue_id' => $issueId);
            $issue = $this->getRepository(Issue::class)->getByParameters($issueQueryParameters, $loggedInUserId);

            WorkLog::addLog($issueId, $loggedInUserId, $timeSpentPost, $dateStartedString, $comment, $currentDate);
            $remainingTime = WorkLog::adjustRemainingEstimate($issue, $timeSpentPost, $remainingTime, $session->get('yongo/settings/time_tracking_hours_per_day'), $session->get('yongo/settings/time_tracking_days_per_week'), $loggedInUserId);

            $fieldChanges = array(array('time_spent', null, $timeSpentPost), array('remaining_estimate', $issue['remaining_estimate'], $remainingTime));
            $this->getRepository(Issue::class)->updateHistory($issue['id'], $loggedInUserId, $fieldChanges, $currentDate);

            // update the date_updated field
            $this->getRepository(Issue::class)->updateById($issueId, array('date_updated' => $currentDate), $currentDate);
        }

        return new Response($remainingTime);
    }
}
