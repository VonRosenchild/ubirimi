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

namespace Ubirimi\Yongo\Controller\Project\Report;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Yongo\Repository\Issue\Issue;

class GetDataController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $projectId = $request->get('project_id');
        $statisticType = $request->get('type');
        $loggedInUserId = $session->get('user/id');

        if ($statisticType == 'assignee') {
            $issueQueryParameters = array('project' => array($projectId));
            $issues = $this->getRepository(Issue::class)->getByParameters($issueQueryParameters, $loggedInUserId, null, $loggedInUserId);

            $issuesAssignee = array();
            while ($issues && $issue = $issues->fetch_array(MYSQLI_ASSOC)) {
                if (!array_key_exists($issue['assignee'], $issuesAssignee)) {
                    $issuesAssignee[$issue['assignee']] = array('assignee_name' => $issue['ua_first_name'] . ' ' . $issue['ua_last_name'],
                        'issues_count' => 0);
                }
                $issuesAssignee[$issue['assignee']]['issues_count']++;
            }
        }
        return new JsonResponse($issuesAssignee);
    }
}
