<?php

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
        $issue_versions_affected = $this->getRepository(IssueVersion::class)->getByIssueIdAndProjectId($issueId, $projectId, Issue::ISSUE_AFFECTED_VERSION_FLAG);
        $arr_issue_versions_affected = array();
        if ($issue_versions_affected) {
            while ($row = $issue_versions_affected->fetch_array(MYSQLI_ASSOC)) {
                $arr_issue_versions_affected[] = $row['project_version_id'];
            }
        }

        $issue_versions_targeted = $this->getRepository(IssueVersion::class)->getByIssueIdAndProjectId($issueId, $projectId, Issue::ISSUE_FIX_VERSION_FLAG);
        $arr_issue_versions_targeted = array();
        if ($issue_versions_targeted) {
            while ($row = $issue_versions_targeted->fetch_array(MYSQLI_ASSOC)) {
                $arr_issue_versions_targeted[] = $row['project_version_id'];
            }
        }
        $allUsers = UbirimiContainer::get()['repository']->get(UbirimiUser::class)->getByClientId($clientId);
        $fieldData = UbirimiContainer::get()['repository']->get(YongoProject::class)->getFieldInformation($project['issue_type_field_configuration_id'], $issueTypeId, 'array');
        $fieldsPlacedOnScreen = array();

        return $this->render(__DIR__ . '/../../Resources/views/issue/EditDialog.php', get_defined_vars());
    }
}