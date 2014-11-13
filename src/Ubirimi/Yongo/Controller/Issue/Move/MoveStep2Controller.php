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
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Issue\IssueComponent;
use Ubirimi\Yongo\Repository\Issue\IssueSettings;
use Ubirimi\Yongo\Repository\Issue\IssueVersion;
use Ubirimi\Yongo\Repository\Project\YongoProject;
use Ubirimi\Yongo\Repository\Workflow\Workflow;


class MoveStep2Controller extends UbirimiController
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

        if ($request->request->has('move_issue_step_2')) {
            $newStatusId = Util::cleanRegularInputField($request->request->get('move_to_status'));

            $session->set('move_issue/new_status', $newStatusId);

            // check if step 3 is necessary

            $issueComponents = $this->getRepository(IssueComponent::class)->getByIssueIdAndProjectId($issue['id'], $projectId);
            $issueFixVersions = $this->getRepository(IssueVersion::class)->getByIssueIdAndProjectId($issue['id'], $projectId, Issue::ISSUE_FIX_VERSION_FLAG);
            $issueAffectedVersions = $this->getRepository(IssueVersion::class)->getByIssueIdAndProjectId($issue['id'], $projectId, Issue::ISSUE_AFFECTED_VERSION_FLAG);

            if ($issueComponents || $issueFixVersions || $issueAffectedVersions) {
                return new RedirectResponse('/yongo/issue/move/fields/' . $issueId);
            } else {
                return new RedirectResponse('/yongo/issue/move/confirmation/' . $issueId);
            }
        }

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Move Issue - ' . $issue['project_code'] . '-' . $issue['nr'] . ' ' . $issue['summary'];
        $currentWorkflow = $this->getRepository(YongoProject::class)->getWorkflowUsedForType($projectId, $issue['type']);

        $previousData = $session->get('move_issue');
        $newWorkflow = $this->getRepository(YongoProject::class)->getWorkflowUsedForType($previousData['new_project'], $previousData['new_type']);
        $newStatuses = $this->getRepository(Workflow::class)->getLinkedStatuses($newWorkflow['id']);
        $menuSelectedCategory = 'issue';

        $newProject = $this->getRepository(YongoProject::class)->getById($session->get('move_issue/new_project'));
        $newProjectName = $newProject['name'];
        $newTypeName = $this->getRepository(IssueSettings::class)->getById($session->get('move_issue/new_type'), 'type', 'name');

        return $this->render(__DIR__ . '/../../../Resources/views/issue/move/MoveStep2.php', get_defined_vars());
    }
}
