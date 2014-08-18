<?php

namespace Ubirimi\Yongo\Controller\Issue;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\LinkHelper;
use Ubirimi\Repository\Client;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Field\Field;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Issue\IssueAttachment;
use Ubirimi\Yongo\Repository\Issue\IssueComponent;
use Ubirimi\Yongo\Repository\Issue\IssueCustomField;
use Ubirimi\Yongo\Repository\Issue\IssueLinkType;
use Ubirimi\Yongo\Repository\Issue\IssueVersion;
use Ubirimi\Yongo\Repository\Issue\IssueWatcher;
use Ubirimi\Yongo\Repository\Issue\IssueWorkLog;
use Ubirimi\Yongo\Repository\Issue\SystemOperation;
use Ubirimi\Yongo\Repository\Permission\Permission;
use Ubirimi\Yongo\Repository\Project\Project;
use Ubirimi\Yongo\Repository\Workflow\Workflow;
use Ubirimi\Yongo\Repository\Workflow\WorkflowStepProperty;

class ViewController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $issueId = $request->get('id');

        if (Util::checkUserIsLoggedIn()) {
            $issue = Issue::getById($issueId, $session->get('user/id'));
            $clientSettings = $session->get('client/settings');
            $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_YONGO);
        } else {
            $clientId = Client::getClientIdAnonymous();
            $session->set('client/id', $clientId);
            $session->set('user/id', null);
            $clientSettings = Client::getSettings($session->get('client/id'));
            $issue = Issue::getById($issueId, $session->get('user/id'));
            $session->set('yongo/settings', Client::getYongoSettings($session->get('client/id')));
        }

        $issueValid = true;

        if ($issue) {
            $projectId = $issue['issue_project_id'];
            $session->set('selected_project_id', $projectId);

            $issueProject = Project::getById($projectId);
            $hasBrowsingPermission = Project::userHasPermission(array($projectId), Permission::PERM_BROWSE_PROJECTS, $session->get('user/id'), $issueId);
        } else {
            $issueValid = false;
        }

        // before going further, check to is if the issue id a valid id
        if (!$issue || !isset($issueProject) || (isset($issueProject) && $session->get('client/id') != $issueProject['client_id'])) {
            $issueValid = false;
        }

        if ($issueValid && !$hasBrowsingPermission) {
            Util::checkUserIsLoggedInAndRedirect('?context=/yongo/issue/' . $issueId);
        }

        if ($issueValid) {

            $sectionPageTitle = $clientSettings['title_name'] . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / ' . $issue['project_code'] . '-' . $issue['nr'] . ' ' . $issue['summary'];

            $components = IssueComponent::getByIssueIdAndProjectId($issueId, $projectId);
            $versionsAffected = IssueVersion::getByIssueIdAndProjectId($issueId, $projectId, Issue::ISSUE_AFFECTED_VERSION_FLAG);
            $versionsTargeted = IssueVersion::getByIssueIdAndProjectId($issueId, $projectId, Issue::ISSUE_FIX_VERSION_FLAG);

            $arrayListResultIds = null;
            if ($session->has('array_ids')) {
                $arrayListResultIds = $session->get('array_ids');
                $index = array_search($issueId, $arrayListResultIds);
            }
            $hasCreatePermission = Project::userHasPermission($projectId, Permission::PERM_CREATE_ISSUE, $session->get('user/id'));

            $hasEditPermission = Project::userHasPermission($projectId, Permission::PERM_EDIT_ISSUE, $session->get('user/id'));

            $hasDeletePermission = Project::userHasPermission($projectId, Permission::PERM_DELETE_ISSUE, $session->get('user/id'));
            $hasAssignPermission = Project::userHasPermission($projectId, Permission::PERM_ASSIGN_ISSUE, $session->get('user/id'));
            $hasAssignablePermission = Project::userHasPermission($projectId, Permission::PERM_ASSIGNABLE_USER, $session->get('user/id'));

            $hasLinkIssuePermission = Project::userHasPermission($projectId, Permission::PERM_LINK_ISSUE, $session->get('user/id'));
            $hasWorkOnIssuePermission = Project::userHasPermission($projectId, Permission::PERM_WORK_ON_ISSUE, $session->get('user/id'));
            $hasMoveIssuePermission = Project::userHasPermission($projectId, Permission::PERM_MOVE_ISSUE, $session->get('user/id'));
            $hasCreateAttachmentPermission = Project::userHasPermission($projectId, Permission::PERM_CREATE_ATTACHMENTS, $session->get('user/id'));

            $hasAddCommentsPermission = Project::userHasPermission($projectId, Permission::PERM_ADD_COMMENTS, $session->get('user/id'));

            $workflowUsed = Project::getWorkflowUsedForType($projectId, $issue[Field::FIELD_ISSUE_TYPE_CODE]);

            $step = Workflow::getStepByWorkflowIdAndStatusId($workflowUsed['id'], $issue[Field::FIELD_STATUS_CODE]);
            $stepProperties = Workflow::getStepProperties($step['id'], 'array');

            $issueEditableProperty = true;
            for ($i = 0; $i < count($stepProperties); $i++) {
                if ($stepProperties[$i]['name'] == WorkflowStepProperty::ISSUE_EDITABLE && $stepProperties[$i]['value'] == 'false')
                    $issueEditableProperty = false;
            }

            $workflowActions = Workflow::getTransitionsForStepId($workflowUsed['id'], $step['id']);
            $screenData = Project::getScreenData($issueProject, $issue[Field::FIELD_ISSUE_TYPE_CODE], SystemOperation::OPERATION_CREATE, 'array');

            $childrenIssues = null;
            $parentIssue = null;
            if ($issue['parent_id'] == null) {
                $childrenIssues = Issue::getByParameters(array('parent_id' => $issue['id']), $session->get('user/id'), null, $session->get('user/id'));
            } else {
                $parentIssue = Issue::getByParameters(array('issue_id' => $issue['parent_id']), $session->get('user/id'), null, $session->get('user/id'));
            }

            $customFieldsData = IssueCustomField::getCustomFieldsData($issue['id']);
            $customFieldsDataUserPickerMultipleUser = IssueCustomField::getUserPickerData($issue['id']);

            $subTaskIssueTypes = Project::getSubTasksIssueTypes($projectId);

            $attachments = IssueAttachment::getByIssueId($issue['id'], true);
            $countAttachments = count($attachments);
            if ($countAttachments) {
                $hasDeleteOwnAttachmentsPermission = Project::userHasPermission($projectId, Permission::PERM_DELETE_OWN_ATTACHMENTS, $session->get('user/id'));
                $hasDeleteAllAttachmentsPermission = Project::userHasPermission($projectId, Permission::PERM_DELETE_OWN_ATTACHMENTS, $session->get('user/id'));
            }

            // get the worklogs
            $workLogs = IssueWorkLog::getByIssueId($issueId);

            // get the watchers, if any
            $watchers = IssueWatcher::getByIssueId($issueId);

            $linkedIssues = IssueLinkType::getLinksByParentId($issueId);
            $linkIssueTypes = IssueLinkType::getByClientId($session->get('client/id'));
            $issueLinkingFlag = $session->get('yongo/settings/issue_linking_flag');
            $hoursPerDay = $session->get('yongo/settings/time_tracking_hours_per_day');
            $daysPerWeek = $session->get('yongo/settings/time_tracking_days_per_week');

            $timeTrackingFlag = $session->get('yongo/settings/time_tracking_flag');

            if ($issueProject['help_desk_enabled_flag']) {
                $slasPrintData = Issue::updateSLAValue($issue, $session->get('client/id'), $clientSettings);
            }

            // voters and watchers
            $hasViewVotersAndWatchersPermission = Project::userHasPermission($projectId, Permission::PERM_VIEW_VOTERS_AND_WATCHERS, $session->get('user/id'));
            $recentIssues = $session->get('yongo/recent_issues');

            if (!isset($recentIssues)) {
                $recentIssues = array();
            }

            $issueFoundInRecent = false;
            for ($i = 0; $i < count($recentIssues); $i++) {
                if ($recentIssues[$i]['link'] == LinkHelper::getYongoIssueViewLink($issue['id'], $issue['nr'], $issue['project_code'], 1)) {
                    $issueFoundInRecent = true;
                    break;
                }
            }

            if (!$issueFoundInRecent) {
                array_unshift($recentIssues, array('summary' => $issue['summary'],
                    'project_code' => $issue['project_code'],
                    'nr' => $issue['nr'],
                    'link' => LinkHelper::getYongoIssueViewLink($issue['id'], $issue['nr'], $issue['project_code'], 1)));
                $recentIssues = array_slice($recentIssues, 0, 5);
            }

            $session->set('yongo/recent_issues', $recentIssues);
        }

        $menuSelectedCategory = 'issue';

        return $this->render(__DIR__ . '/../../Resources/views/issue/View.php', get_defined_vars());
    }
}