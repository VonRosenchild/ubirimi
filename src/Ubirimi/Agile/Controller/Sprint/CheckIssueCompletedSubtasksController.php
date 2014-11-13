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

namespace Ubirimi\Agile\Controller\Sprint;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Field\Field;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Issue\IssueEvent;
use Ubirimi\Yongo\Repository\Permission\Permission;
use Ubirimi\Yongo\Repository\Project\YongoProject;
use Ubirimi\Yongo\Repository\Workflow\Workflow;

class CheckIssueCompletedSubtasksController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $issueId = $request->request->get('id');

        $statuses = $request->request->get('statuses');
        $statusesIds = explode('_', $statuses);

        $parameters = array('parent_id' => $issueId);
        $childrenIssue = $this->getRepository(Issue::class)->getByParameters($parameters);

        while ($issue = $childrenIssue->fetch_array(MYSQLI_ASSOC)) {
            if (!in_array($issue['status'], $statusesIds)) {
                echo 'no';
                die();
            }
        }

        $issue = $this->getRepository(Issue::class)->getByParameters(array('issue_id' => $issueId), $session->get('user/id'));
        if (in_array($issue['status'], $statusesIds)) {
            return new Response('no');
        }

        $projectId = $issue['issue_project_id'];
        $workflowUsed = $this->getRepository(YongoProject::class)->getWorkflowUsedForType($projectId, $issue[Field::FIELD_ISSUE_TYPE_CODE]);

        $step = $this->getRepository(Workflow::class)->getStepByWorkflowIdAndStatusId($workflowUsed['id'], $issue[Field::FIELD_STATUS_CODE]);
        $workflowActions = $this->getRepository(Workflow::class)->getTransitionsForStepId($workflowUsed['id'], $step['id']);

        $data = array();
        while ($workflowActions && $workflowStep = $workflowActions->fetch_array(MYSQLI_ASSOC)) {
            if (!in_array($workflowStep['status'], $statusesIds))
                continue;

            $workflowDataId = $workflowStep['id'];

            $transitionEvent = $this->getRepository(IssueEvent::class)->getEventByWorkflowDataId($workflowDataId);
            $hasEventPermission = false;

            switch ($transitionEvent['code']) {

                case IssueEvent::EVENT_ISSUE_CLOSED_CODE:
                    $hasEventPermission = $this->getRepository(YongoProject::class)->userHasPermission($projectId, Permission::PERM_CLOSE_ISSUE, $session->get('user/id'));
                    break;

                case IssueEvent::EVENT_ISSUE_REOPENED_CODE:

                case IssueEvent::EVENT_ISSUE_RESOLVED_CODE:

                    $hasEventPermission = $this->getRepository(YongoProject::class)->userHasPermission($projectId, Permission::PERM_RESOLVE_ISSUE, $session->get('user/id'));
                    break;

                case IssueEvent::EVENT_ISSUE_WORK_STARTED_CODE:
                case IssueEvent::EVENT_ISSUE_WORK_STOPPED_CODE:

                    $hasEventPermission = $this->getRepository(YongoProject::class)->userHasPermission($projectId, Permission::PERM_EDIT_ISSUE, $session->get('user/id'));
                    break;
                case IssueEvent::EVENT_GENERIC_CODE:
                    $hasEventPermission = true;
                    break;
            }

            $canBeExecuted = $this->getRepository(Workflow::class)->checkConditionsByTransitionId($workflowStep['id'], $session->get('user/id'), $issue);
            $value = array();

            if ($hasEventPermission && $canBeExecuted) {
                $value['transition_name'] = $workflowStep['transition_name'];
                if ($workflowStep['screen_id'])
                    $value['screen'] = 1;
                else
                    $value['screen'] = 0;
                $value['step_id_from'] = $step['id'];
                $value['step_id_to'] = $workflowStep['workflow_step_id_to'];
                $value['workflow_id'] = $workflowUsed['id'];
                $value['project_id'] = $projectId;
                $data[] = $value;
            }
        }

        return new Response(json_encode($data));
    }
}
