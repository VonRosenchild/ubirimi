<?php

/*
 *  Copyright (C) 2012-2014 SC Ubirimi SRL <info-copyright@ubirimi.com>
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License version 2 as
 *  published by the Free Software Foundation.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301, USA.
 */

namespace Ubirimi\Yongo\Controller\Issue\LogWork;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Event\IssueEvent;
use Ubirimi\Yongo\Event\YongoEvents;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Issue\WorkLog;
use Ubirimi\Yongo\Repository\Project\YongoProject;

class DeleteController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $loggedInUserId = $session->get('user/id');

        $workLogId = $request->request->get('id');
        $issueId = $request->request->get('issue_id');
        $remainingTime = $request->request->get('remaining');

        $workLog = $this->getRepository(WorkLog::class)->getById($workLogId);
        $timeSpent = $workLog['time_spent'];

        $this->getRepository(WorkLog::class)->deleteById($workLogId);

        $issueQueryParameters = array('issue_id' => $issueId);
        $issue = $this->getRepository(Issue::class)->getByParameters($issueQueryParameters, $session->get('user/id'));
        $previousEstimate = $issue['remaining_estimate'];

        if ($remainingTime == 'automatic') {
            $remainingTime = '+' . $timeSpent;
        }

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

        // send the email notification
        $project = $this->getRepository(YongoProject::class)->getById($issue['issue_project_id']);
        $issueEventData = array('user_id' => $loggedInUserId,
                                'remaining_estimate' => $remainingTime,
                                'time_spent' => $workLog['time_spent']);
        $issueEvent = new IssueEvent($issue, $project, IssueEvent::STATUS_UPDATE, $issueEventData);

        UbirimiContainer::get()['dispatcher']->dispatch(YongoEvents::YONGO_ISSUE_WORK_LOG_DELETED, $issueEvent);

        return new Response($remainingTime);
    }
}
