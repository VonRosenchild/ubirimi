<?php

namespace Ubirimi\Yongo\Controller\Issue;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\LinkHelper;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Field\Field;
use Ubirimi\Yongo\Repository\Issue\CustomField;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Issue\IssueAttachment;
use Ubirimi\Yongo\Repository\Issue\LinkType;
use Ubirimi\Yongo\Repository\Issue\SystemOperation;
use Ubirimi\Yongo\Repository\Issue\Watcher;
use Ubirimi\Yongo\Repository\Issue\WorkLog;
use Ubirimi\Yongo\Repository\Permission\Permission;
use Ubirimi\Yongo\Repository\Workflow\StepProperty;
use Ubirimi\Yongo\Repository\Project\YongoProject;
use Ubirimi\Yongo\Repository\Issue\IssueComponent;
use Ubirimi\Yongo\Repository\Issue\IssueVersion;
use Ubirimi\Yongo\Repository\Workflow\Workflow;

class ViewController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $issueId = $request->get('id');

        if (Util::checkUserIsLoggedIn()) {
            $issue = $this->getRepository(Issue::class)->getById($issueId, $session->get('user/id'));
            $clientSettings = $session->get('client/settings');
            $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_YONGO);
        } else {
            $clientId = $this->getRepository(UbirimiClient::class)->getClientIdAnonymous();
            $session->set('client/id', $clientId);
            $session->set('user/id', null);
            $clientSettings = $this->getRepository(UbirimiClient::class)->getSettings($session->get('client/id'));
            $issue = $this->getRepository(Issue::class)->getById($issueId, $session->get('user/id'));
            $session->set('yongo/settings', $this->getRepository(UbirimiClient::class)->getYongoSettings($session->get('client/id')));
        }

        $issueValid = true;
        if ($issue) {
            $projectId = $issue['issue_project_id'];
            $session->set('selected_project_id', $projectId);

            $issueProject = $this->getRepository(YongoProject::class)->getById($projectId);
            $hasBrowsingPermission = $this->getRepository(YongoProject::class)->userHasPermission(array($projectId), Permission::PERM_BROWSE_PROJECTS, $session->get('user/id'), $issueId);
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

            $components = $this->getRepository(IssueComponent::class)->getByIssueIdAndProjectId($issueId, $projectId);
            $versionsAffected = $this->getRepository(IssueVersion::class)->getByIssueIdAndProjectId($issueId, $projectId, Issue::ISSUE_AFFECTED_VERSION_FLAG);
            $versionsTargeted = $this->getRepository(IssueVersion::class)->getByIssueIdAndProjectId($issueId, $projectId, Issue::ISSUE_FIX_VERSION_FLAG);

            $arrayListResultIds = null;
            if ($session->has('array_ids')) {
                $arrayListResultIds = $session->get('array_ids');
                $index = array_search($issueId, $arrayListResultIds);
            }
            $hasCreatePermission = $this->getRepository(YongoProject::class)->userHasPermission($projectId, Permission::PERM_CREATE_ISSUE, $session->get('user/id'));
            $hasEditPermission = $this->getRepository(YongoProject::class)->userHasPermission($projectId, Permission::PERM_EDIT_ISSUE, $session->get('user/id'));

            $hasDeletePermission = $this->getRepository(YongoProject::class)->userHasPermission($projectId, Permission::PERM_DELETE_ISSUE, $session->get('user/id'));
            $hasAssignPermission = $this->getRepository(YongoProject::class)->userHasPermission($projectId, Permission::PERM_ASSIGN_ISSUE, $session->get('user/id'));
            $hasAssignablePermission = $this->getRepository(YongoProject::class)->userHasPermission($projectId, Permission::PERM_ASSIGNABLE_USER, $session->get('user/id'));

            $hasLinkIssuePermission = $this->getRepository(YongoProject::class)->userHasPermission($projectId, Permission::PERM_LINK_ISSUE, $session->get('user/id'));
            $hasWorkOnIssuePermission = $this->getRepository(YongoProject::class)->userHasPermission($projectId, Permission::PERM_WORK_ON_ISSUE, $session->get('user/id'));
            $hasMoveIssuePermission = $this->getRepository(YongoProject::class)->userHasPermission($projectId, Permission::PERM_MOVE_ISSUE, $session->get('user/id'));
            $hasCreateAttachmentPermission = $this->getRepository(YongoProject::class)->userHasPermission($projectId, Permission::PERM_CREATE_ATTACHMENTS, $session->get('user/id'));

            $hasAddCommentsPermission = $this->getRepository(YongoProject::class)->userHasPermission($projectId, Permission::PERM_ADD_COMMENTS, $session->get('user/id'));

            $workflowUsed = $this->getRepository(YongoProject::class)->getWorkflowUsedForType($projectId, $issue[Field::FIELD_ISSUE_TYPE_CODE]);

            $step = $this->getRepository(Workflow::class)->getStepByWorkflowIdAndStatusId($workflowUsed['id'], $issue[Field::FIELD_STATUS_CODE]);
            $stepProperties = $this->getRepository(Workflow::class)->getStepProperties($step['id'], 'array');

            $issueEditableProperty = true;
            for ($i = 0; $i < count($stepProperties); $i++) {
                if ($stepProperties[$i]['name'] == StepProperty::ISSUE_EDITABLE && $stepProperties[$i]['value'] == 'false')
                    $issueEditableProperty = false;
            }

            $workflowActions = $this->getRepository(Workflow::class)->getTransitionsForStepId($workflowUsed['id'], $step['id']);
            $screenData = $this->getRepository(YongoProject::class)->getScreenData($issueProject, $issue[Field::FIELD_ISSUE_TYPE_CODE], SystemOperation::OPERATION_CREATE, 'array');

            $childrenIssues = null;
            $parentIssue = null;
            if ($issue['parent_id'] == null) {
                $childrenIssues = UbirimiContainer::get()['repository']->get(Issue::class)->getByParameters(array('parent_id' => $issue['id']), $session->get('user/id'), null, $session->get('user/id'));
            } else {
                $parentIssue = $this->getRepository(Issue::class)->getByParameters(array('issue_id' => $issue['parent_id']), $session->get('user/id'), null, $session->get('user/id'));
            }

            $customFieldsData = $this->getRepository(CustomField::class)->getCustomFieldsData($issue['id']);
            $customFieldsDataUserPickerMultipleUser = $this->getRepository(CustomField::class)->getUserPickerData($issue['id']);

            $subTaskIssueTypes = $this->getRepository(YongoProject::class)->getSubTasksIssueTypes($projectId);

            $attachments = $this->getRepository(IssueAttachment::class)->getByIssueId($issue['id'], true);
            $countAttachments = count($attachments);
            if ($countAttachments) {
                $hasDeleteOwnAttachmentsPermission = $this->getRepository(YongoProject::class)->userHasPermission($projectId, Permission::PERM_DELETE_OWN_ATTACHMENTS, $session->get('user/id'));
                $hasDeleteAllAttachmentsPermission = $this->getRepository(YongoProject::class)->userHasPermission($projectId, Permission::PERM_DELETE_OWN_ATTACHMENTS, $session->get('user/id'));
            }

            // get the worklogs
            $workLogs = $this->getRepository(WorkLog::class)->getByIssueId($issueId);

            // get the watchers, if any
            $watchers = $this->getRepository(Watcher::class)->getByIssueId($issueId);

            $linkedIssues = $this->getRepository(LinkType::class)->getLinksByParentId($issueId);
            $linkIssueTypes = $this->getRepository(LinkType::class)->getByClientId($session->get('client/id'));
            $issueLinkingFlag = $session->get('yongo/settings/issue_linking_flag');
            $hoursPerDay = $session->get('yongo/settings/time_tracking_hours_per_day');
            $daysPerWeek = $session->get('yongo/settings/time_tracking_days_per_week');

            $timeTrackingFlag = $session->get('yongo/settings/time_tracking_flag');

            $slasPrintData = null;
            if ($issueProject['help_desk_enabled_flag']) {
                $slasPrintData = $this->getRepository(Issue::class)->updateSLAValue($issue, $session->get('client/id'), $clientSettings);
            }

            // voters and watchers
            $hasViewVotersAndWatchersPermission = $this->getRepository(YongoProject::class)->userHasPermission($projectId, Permission::PERM_VIEW_VOTERS_AND_WATCHERS, $session->get('user/id'));
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