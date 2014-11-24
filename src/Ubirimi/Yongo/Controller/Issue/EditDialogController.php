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

namespace Ubirimi\Yongo\Controller\Issue;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Issue\IssueComponent;
use Ubirimi\Yongo\Repository\Issue\IssueSecurityScheme;
use Ubirimi\Yongo\Repository\Issue\IssueSettings;
use Ubirimi\Yongo\Repository\Issue\IssueVersion;
use Ubirimi\Yongo\Repository\Issue\SystemOperation;
use Ubirimi\Yongo\Repository\Permission\Permission;
use Ubirimi\Yongo\Repository\Project\YongoProject;

class EditDialogController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientId = $session->get('client/id');
        $loggedInUserId = $session->get('user/id');

        $issueId = $request->get('id');
        $issueData = UbirimiContainer::get()['repository']->get(Issue::class)->getByParameters(array('issue_id' => $issueId), $session->get('user/id'), null, $session->get('user/id'));
        $issueTypeId = $issueData['issue_type_id'];

        $issueId = $issueData['id'];
        $projectId = $issueData['issue_project_id'];
        $project = UbirimiContainer::get()['repository']->get(YongoProject::class)->getById($projectId);

        $screenData = UbirimiContainer::get()['repository']->get(YongoProject::class)->getScreenData($project, $issueTypeId, SystemOperation::OPERATION_EDIT);

        $reporterUsers = UbirimiContainer::get()['repository']->get(YongoProject::class)->getUsersWithPermission($projectId, Permission::PERM_CREATE_ISSUE);
        $issuePriorities = $this->getRepository(IssueSettings::class)->getAllIssueSettings('priority', $clientId);
        $projectIssueTypes = UbirimiContainer::get()['repository']->get(YongoProject::class)->getIssueTypes($projectId, 0);

        // check to see if the issue type is a sub-task issue type. if yes then show only sub task issue types
        $projectSubTaskIssueTypes = UbirimiContainer::get()['repository']->get(YongoProject::class)->getSubTasksIssueTypes($projectId, 'array', 'id');
        if ($projectSubTaskIssueTypes && in_array($issueTypeId, $projectSubTaskIssueTypes)) {
            $projectIssueTypes = UbirimiContainer::get()['repository']->get(YongoProject::class)->getSubTasksIssueTypes($projectId);
        } else {
            $projectIssueTypes = UbirimiContainer::get()['repository']->get(YongoProject::class)->getIssueTypes($projectId, 0);
        }

        $assignableUsers = UbirimiContainer::get()['repository']->get(YongoProject::class)->getUsersWithPermission($projectId, Permission::PERM_ASSIGNABLE_USER);
        $userHasModifyReporterPermission = UbirimiContainer::get()['repository']->get(YongoProject::class)->userHasPermission($projectId, Permission::PERM_MODIFY_REPORTER, $loggedInUserId);
        $userHasAssignIssuePermission = UbirimiContainer::get()['repository']->get(YongoProject::class)->userHasPermission($projectId, Permission::PERM_ASSIGN_ISSUE, $loggedInUserId);
        $userHasSetSecurityLevelPermission = UbirimiContainer::get()['repository']->get(YongoProject::class)->userHasPermission($projectId, Permission::PERM_SET_SECURITY_LEVEL, $loggedInUserId);

        $timeTrackingFieldId = null;
        $timeTrackingFlag = $session->get('yongo/settings/time_tracking_flag');

        $issueSecuritySchemeId = $project['issue_security_scheme_id'];
        $issueSecuritySchemeLevels = null;
        if ($issueSecuritySchemeId) {
            $issueSecuritySchemeLevels = $this->getRepository(IssueSecurityScheme::class)->getLevelsByIssueSecuritySchemeId($issueSecuritySchemeId);
        }

        $projectComponents = UbirimiContainer::get()['repository']->get(YongoProject::class)->getComponents($projectId);
        $issueComponents = $this->getRepository(IssueComponent::class)->getByIssueIdAndProjectId($issueId, $projectId);
        $arrIssueComponents = array();

        if ($issueComponents) {
            while ($row = $issueComponents->fetch_array(MYSQLI_ASSOC)) {
                $arrIssueComponents[] = $row['project_component_id'];
            }
        }

        $projectVersions = UbirimiContainer::get()['repository']->get(YongoProject::class)->getVersions($projectId);
        $issueVersionsAffected = $this->getRepository(IssueVersion::class)->getByIssueIdAndProjectId($issueId, $projectId, Issue::ISSUE_AFFECTED_VERSION_FLAG);
        $arrayIssueVersionsAffected = array();
        if ($issueVersionsAffected) {
            while ($row = $issueVersionsAffected->fetch_array(MYSQLI_ASSOC)) {
                $arrayIssueVersionsAffected[] = $row['project_version_id'];
            }
        }

        $issueVersionsTargeted = $this->getRepository(IssueVersion::class)->getByIssueIdAndProjectId($issueId, $projectId, Issue::ISSUE_FIX_VERSION_FLAG);
        $arrayIssueVersionsTargeted = array();
        if ($issueVersionsTargeted) {
            while ($row = $issueVersionsTargeted->fetch_array(MYSQLI_ASSOC)) {
                $arrayIssueVersionsTargeted[] = $row['project_version_id'];
            }
        }
        $allUsers = UbirimiContainer::get()['repository']->get(UbirimiUser::class)->getByClientId($clientId);
        $fieldData = UbirimiContainer::get()['repository']->get(YongoProject::class)->getFieldInformation($project['issue_type_field_configuration_id'], $issueTypeId, 'array');
        $fieldsPlacedOnScreen = array();

        return $this->render(__DIR__ . '/../../Resources/views/issue/EditDialog.php', get_defined_vars());
    }
}