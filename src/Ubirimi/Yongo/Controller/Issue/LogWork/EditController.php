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

class EditController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $loggedInUserId = $session->get('user/id');

        $workLogId = $request->request->get('id');
        $issueId = $request->request->get('issue_id');
        $timeSpent = trim(str_replace(" ", '', $request->request->get('time_spent')));
        $dateStartedString = $request->request->get('date_started');
        $remainingTimePost = $request->request->get('remaining');
        $comment = $request->request->get('comment');

        $dateStarted = \DateTime::createFromFormat('d-m-Y H:i', $dateStartedString);
        $dateStartedString = date_format($dateStarted, 'Y-m-d H:i');

        $workLog = $this->getRepository(WorkLog::class)->getById($workLogId);

        $this->getRepository(WorkLog::class)->updateLogById($workLogId, $timeSpent, $dateStartedString, $comment);

        $issueQueryParameters = array('issue_id' => $issueId);
        $issue = $this->getRepository(Issue::class)->getByParameters($issueQueryParameters, $session->get('user/id'));

        $remaining = $this->getRepository(WorkLog::class)->adjustRemainingEstimate(
            $issue,
            null,
            "+" . $workLog['time_spent'],
            $session->get('yongo/settings/time_tracking_hours_per_day'),
            $session->get('yongo/settings/time_tracking_days_per_week'),
            $session->get('user/id')
        );

        $previousIssueRemainingEstimate = $issue['remaining_estimate'];

        $issue['remaining_estimate'] = $remaining;

        $remainingTimePost = $this->getRepository(WorkLog::class)->adjustRemainingEstimate(
            $issue,
            $timeSpent,
            $remainingTimePost,
            $session->get('yongo/settings/time_tracking_hours_per_day'),
            $session->get('yongo/settings/time_tracking_days_per_week'),
            $session->get('user/id')
        );

        // update the history
        $currentDate = Util::getServerCurrentDateTime();
        $fieldChanges = array(
            array('time_spent', $workLog['time_spent'], $timeSpent),
            array('remaining_estimate', $previousIssueRemainingEstimate, $remainingTimePost)
        );

        $this->getRepository(Issue::class)->updateHistory($issue['id'], $session->get('user/id'), $fieldChanges, $currentDate);

        // update the date_updated field
        $this->getRepository(Issue::class)->updateById($issueId, array('date_updated' => $currentDate), $currentDate);

        $project = $this->getRepository(YongoProject::class)->getById($issue['issue_project_id']);
        $issueEventData = array('user_id' => $loggedInUserId,
            'comment' => $comment,
            'date_started' => $dateStartedString,
            'remaining_time' => $remainingTimePost,
            'time_spent' => $timeSpent);
        $issueEvent = new IssueEvent($issue, $project, IssueEvent::STATUS_UPDATE, $issueEventData);

        UbirimiContainer::get()['dispatcher']->dispatch(YongoEvents::YONGO_ISSUE_WORK_LOG_UPDATED, $issueEvent);

        return new Response($remainingTimePost);
    }
}