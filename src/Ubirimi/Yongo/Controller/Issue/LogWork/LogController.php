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

            $this->getRepository(WorkLog::class)->addLog($issueId, $loggedInUserId, $timeSpentPost, $dateStartedString, $comment, $currentDate);
            $remainingTime = $this->getRepository(WorkLog::class)->adjustRemainingEstimate($issue, $timeSpentPost, $remainingTime, $session->get('yongo/settings/time_tracking_hours_per_day'), $session->get('yongo/settings/time_tracking_days_per_week'), $loggedInUserId);

            $fieldChanges = array(array('time_spent', null, $timeSpentPost), array('remaining_estimate', $issue['remaining_estimate'], $remainingTime));
            $this->getRepository(Issue::class)->updateHistory($issue['id'], $loggedInUserId, $fieldChanges, $currentDate);

            // update the date_updated field
            $this->getRepository(Issue::class)->updateById($issueId, array('date_updated' => $currentDate), $currentDate);

            $project = $this->getRepository(YongoProject::class)->getById($issue['issue_project_id']);
            $issueEventData = array('user_id' => $loggedInUserId,
                                    'comment' => $comment,
                                    'date_started' => $dateStartedString,
                                    'time_spent' => $timeSpentPost);
            $issueEvent = new IssueEvent($issue, $project, IssueEvent::STATUS_UPDATE, $issueEventData);

            UbirimiContainer::get()['dispatcher']->dispatch(YongoEvents::YONGO_ISSUE_WORK_LOGGED, $issueEvent);

        }

        if (null == $remainingTime || '' == $remainingTime) {
            $remainingTime = -1;
        }

        return new Response($remainingTime);
    }
}
