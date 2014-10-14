<?php

namespace Ubirimi\Yongo\Controller\Issue;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class TimeTrackingController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $loggedInUserId = $session->get('user/id');

        $issueId = $request->request->get('id');

        $issueQueryParameters = array('issue_id' => $issueId);
        $issue = $this->getRepository('yongo.issue.issue')->getByParameters($issueQueryParameters, $loggedInUserId);

        $hoursPerDay = $session->get('yongo/settings/time_tracking_hours_per_day');
        $daysPerWeek = $session->get('yongo/settings/time_tracking_days_per_week');

        return $this->render(__DIR__ . '/../../Resources/views/issue/_timeTrackingInformation.php', get_defined_vars());
    }
}