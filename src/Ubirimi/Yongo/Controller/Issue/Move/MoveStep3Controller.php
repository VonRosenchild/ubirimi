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
use Ubirimi\Yongo\Repository\Permission\Permission;
use Ubirimi\Yongo\Repository\Project\YongoProject;


class MoveStep3Controller extends UbirimiController
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

        if ($request->request->has('move_issue_step_3')) {

            $newIssueComponents = $request->request->get('new_component');
            $newIssueFixVersions = $request->request->get('new_fix_version');
            $newIssueAffectsVersions = $request->request->get('new_affects_version');

            if (array_key_exists('new_assignee', $_POST)) {
                $session->set('move_issue/new_assignee', $request->request->get('new_assignee'));
            }

            $session->set('move_issue/new_component', $newIssueComponents);
            $session->set('move_issue/new_fix_version', $newIssueFixVersions);
            $session->set('move_issue/new_affects_version', $newIssueAffectsVersions);

            return new RedirectResponse('/yongo/issue/move/confirmation/' . $issueId);
        }

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Move Issue - ' . $issue['project_code'] . '-' . $issue['nr'] . ' ' . $issue['summary'];

        $menuSelectedCategory = 'issue';

        $targetProjectId = $session->get('move_issue/new_project');
        $targetProjectComponents = $this->getRepository(YongoProject::class)->getComponents($targetProjectId);
        $targetVersions = $this->getRepository(YongoProject::class)->getVersions($targetProjectId);

        $issueComponents = $this->getRepository(IssueComponent::class)->getByIssueIdAndProjectId($issue['id'], $projectId);
        $issueFixVersions = $this->getRepository(IssueVersion::class)->getByIssueIdAndProjectId($issue['id'], $projectId, Issue::ISSUE_FIX_VERSION_FLAG);
        $issueAffectedVersions = $this->getRepository(IssueVersion::class)->getByIssueIdAndProjectId($issue['id'], $projectId, Issue::ISSUE_AFFECTED_VERSION_FLAG);

        $sourceAssignee = $issue['assignee'];
        $assignableUsersTargetProjectArray = $this->getRepository(YongoProject::class)->getUsersWithPermission($session->get('move_issue/new_project'), Permission::PERM_ASSIGNABLE_USER, 'array');

        $assigneeChanged = true;

        if ($sourceAssignee) {
            for ($i = 0; $i < count($assignableUsersTargetProjectArray); $i++) {
                if ($sourceAssignee == $assignableUsersTargetProjectArray[$i]['user_id']) {
                    $assigneeChanged = false;
                    break;
                }
            }
        }

        $actionTaken = false;
        if ((($issueComponents || $issueFixVersions || $issueAffectedVersions) && ($targetProjectComponents || $targetVersions)) || $assigneeChanged) {
            $actionTaken = true;
        }
        $newStatusName = $this->getRepository(IssueSettings::class)->getById($session->get('move_issue/new_status'), 'status', 'name');

        $newProject = $this->getRepository(YongoProject::class)->getById($session->get('move_issue/new_project'));
        $newProjectName = $newProject['name'];
        $newTypeName = $this->getRepository(IssueSettings::class)->getById($session->get('move_issue/new_type'), 'type', 'name');

        return $this->render(__DIR__ . '/../../../Resources/views/issue/move/MoveStep3.php', get_defined_vars());
    }
}