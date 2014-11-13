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
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Project\YongoProject;
use Ubirimi\Yongo\Repository\Workflow\Workflow;

class MoveStep1SubtaskController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientId = $session->get('client/id');
        $loggedInUserId = $session->get('user/id');

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
        $errorNoNewSubtaskIssueTypeSelected = false;
        if ($request->request->has('move_issue_step_1_subtask')) {

            // keep the new sub task issue types
            $newSubtaskIssueTypeId = null;
            foreach ($_POST as $key => $value) {
                if (substr($key, 0, 23) == 'new_subtask_issue_type_') {
                    $oldSubtaskIssueTypeId = str_replace('new_subtask_issue_type_', '', $key);
                    $newSubtaskIssueTypeId = $_POST[$key];
                    UbirimiContainer::get()['session']->set('move_issue/sub_task_new_issue_type', array($oldSubtaskIssueTypeId, $newSubtaskIssueTypeId));
                }
            }

            // check if step 2 is necessary
            $newWorkflow = $this->getRepository(YongoProject::class)->getWorkflowUsedForType(UbirimiContainer::get()['session']->get('move_issue/new_project'), UbirimiContainer::get()['session']->get('move_issue/new_type'));
            $newStatuses = $this->getRepository(Workflow::class)->getLinkedStatuses($newWorkflow['id']);

            $step2Necessary = true;
            while ($newStatuses && $status = $newStatuses->fetch_array(MYSQLI_ASSOC)) {
                if ($status['linked_issue_status_id'] == $issue['status']) {
                    $step2Necessary = false;
                }
            }

            if ($newSubtaskIssueTypeId) {
                if ($step2Necessary) {
                    return new RedirectResponse('/yongo/issue/move/status/' . $issueId);
                } else {
                    UbirimiContainer::get()['session']->set('move_issue/new_status', $issue['status']);
                    return new RedirectResponse('/yongo/issue/move/fields/' . $issueId);
                }
            } else {
                $errorNoNewSubtaskIssueTypeSelected = true;
            }
        }
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Move Issue - ' . $issue['project_code'] . '-' . $issue['nr'] . ' ' . $issue['summary'];

        $menuSelectedCategory = 'issue';

        $oldSubtaskIssueType = UbirimiContainer::get()['session']->get('move_issue/sub_task_old_issue_type');
        $newSubtaskIssueType = $this->getRepository(YongoProject::class)->getSubTasksIssueTypes(UbirimiContainer::get()['session']->get('move_issue/new_project'));

        return $this->render(__DIR__ . '/../../../Resources/views/issue/move/MoveStep1Subtask.php', get_defined_vars());
    }
}