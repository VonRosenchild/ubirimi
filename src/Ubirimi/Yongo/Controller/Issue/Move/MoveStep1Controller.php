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

namespace Ubirimi\Yongo\Controller\Issue\Move;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Permission\Permission;
use Ubirimi\Yongo\Repository\Project\YongoProject;
use Ubirimi\Yongo\Repository\Workflow\Workflow;

class MoveStep1Controller extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $loggedInUserId = $session->get('user/id');
        $clientId = $session->get('client/id');

        $issueId = $request->get('id');

        $issueQueryParameters = array('issue_id' => $issueId);
        $issue = $this->getRepository(Issue::class)->getByParameters($issueQueryParameters, $loggedInUserId);
        $projectId = $issue['issue_project_id'];
        $issueProject = $this->getRepository(YongoProject::class)->getById($projectId);

        // before going further, check to is if the issue project belongs to the client
        if ($clientId != $issueProject['client_id']) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_YONGO);

        if ($request->request->has('move_issue_step_1')) {
            $newProjectId = Util::cleanRegularInputField($request->request->get('move_to_project'));
            $newIssueTypeId = Util::cleanRegularInputField($request->request->get('move_to_issue_type'));

            UbirimiContainer::get()['session']->set('move_issue', array(
                    'id' => $issueId,
                    'new_project' => $newProjectId,
                    'new_type' => $newIssueTypeId,
                    'new_component' => array(),
                    'new_fix_version' => array(),
                    'new_affects_version' => array(),
                    'sub_task_old_issue_type' => array(),
                    'sub_task_new_issue_type' => array())
            );

            $childrenIssues = null;
            if ($issue['parent_id'] == null) {
                $childrenIssues = $this->getRepository(Issue::class)->getByParameters(array('parent_id' => $issue['id']));
            }

            $newProjectIssueTypes = $this->getRepository(YongoProject::class)->getIssueTypes($newProjectId, 0, 'array', 'id');
            $selectIssueTypeForSubstaks = false;

            if ($childrenIssues) {
                while ($childrenIssues && $childIssue = $childrenIssues->fetch_array(MYSQLI_ASSOC)) {
                    if (!in_array($childIssue['type'], $newProjectIssueTypes)) {
                        $tempArray = UbirimiContainer::get()['session']->get('move_issue/sub_task_old_issue_type');
                        $tempArray[] = $childIssue['type'];
                        UbirimiContainer::get()['session']->set('move_issue/sub_task_old_issue_type', $tempArray);
                        $selectIssueTypeForSubtasks = true;
                    }
                }
            }

            if ($selectIssueTypeForSubtasks) {
                return new RedirectResponse('/yongo/issue/move/subtask-issue-type/' . $issueId);
            } else {
                // check if step 2 is necessary
                $newWorkflow = $this->getRepository(YongoProject::class)->getWorkflowUsedForType($newProjectId, $newIssueTypeId);
                $newStatuses = $this->getRepository(Workflow::class)->getLinkedStatuses($newWorkflow['id']);

                $step2Necessary = true;
                while ($newStatuses && $status = $newStatuses->fetch_array(MYSQLI_ASSOC)) {
                    if ($status['linked_issue_status_id'] == $issue['status']) {
                        $step2Necessary = false;
                    }
                }

                if ($step2Necessary) {
                    return new RedirectResponse('/yongo/issue/move/status/' . $issueId);
                } else {
                    UbirimiContainer::get()['session']->set('move_issue/new_status', $issue['status']);
                    return new RedirectResponse('/yongo/issue/move/fields/' . $issueId);
                }
            }
        }
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Move Issue - ' . $issue['project_code'] . '-' . $issue['nr'] . ' ' . $issue['summary'];
        $projectForMoving = $this->getRepository(UbirimiClient::class)->getProjectsByPermission($session->get('client/id'), $loggedInUserId, Permission::PERM_CREATE_ISSUE);
        $firstProject = $projectForMoving->fetch_array(MYSQLI_ASSOC);

        $moveToIssueTypes = $this->getRepository(YongoProject::class)->getIssueTypes($firstProject['id'], 0);
        $projectForMoving->data_seek(0);
        $menuSelectedCategory = 'issue';

        return $this->render(__DIR__ . '/../../../Resources/views/issue/move/MoveStep1.php', get_defined_vars());
    }
}
