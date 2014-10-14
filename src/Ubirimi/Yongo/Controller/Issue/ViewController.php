<?php

namespace Ubirimi\Yongo\Controller\Issue;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\LinkHelper;
use Ubirimi\Repository\Client;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Field\Field;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Issue\Attachment;
use Ubirimi\Yongo\Repository\Issue\Component;
use Ubirimi\Yongo\Repository\Issue\CustomField;
use Ubirimi\Yongo\Repository\Issue\LinkType;
use Ubirimi\Yongo\Repository\Issue\Version;
use Ubirimi\Yongo\Repository\Issue\Watcher;
use Ubirimi\Yongo\Repository\Issue\WorkLog;
use Ubirimi\Yongo\Repository\Issue\SystemOperation;
use Ubirimi\Yongo\Repository\Permission\Permission;
use Ubirimi\Yongo\Repository\Project\Project;
use Ubirimi\Yongo\Repository\Workflow\Workflow;
use Ubirimi\Yongo\Repository\Workflow\StepProperty;

class ViewController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $issueId = $request->get('id');

        if (Util::checkUserIsLoggedIn()) {
            $issue = $this->getRepository('yongo.issue.issue')->getById($issueId, $session->get('user/id'));
            $clientSettings = $session->get('client/settings');
            $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_YONGO);
        } else {
            $clientId = $this->getRepository('ubirimi.general.client')->getClientIdAnonymous();
            $session->set('client/id', $clientId);
            $session->set('user/id', null);
            $clientSettings = $this->getRepository('ubirimi.general.client')->getSettings($session->get('client/id'));
            $issue = $this->getRepository('yongo.issue.issue')->getById($issueId, $session->get('user/id'));
            $session->set('yongo/settings', $this->getRepository('ubirimi.general.client')->getYongoSettings($session->get('client/id')));
        }

        $issueValid = true;

        if ($issue) {
            $projectId = $issue['issue_project_id'];
            $session->set('selected_project_id', $projectId);

            $issueProject = $this->getRepository('yongo.project.project')->getById($projectId);
            $hasBrowsingPermission = $this->getRepository('yongo.project.project')->userHasPermission(array($projectId), Permission::PERM_BROWSE_PROJECTS, $session->get('user/id'), $issueId);
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

            $components = Component::getByIssueIdAndProjectId($issueId, $projectId);
            $versionsAffected = Version::getByIssueIdAndProjectId($issueId, $projectId, Issue::ISSUE_AFFECTED_VERSION_FLAG);
            $versionsTargeted = Version::getByIssueIdAndProjectId($issueId, $projectId, Issue::ISSUE_FIX_VERSION_FLAG);

            $arrayListResultIds = null;
            if ($session->has('array_ids')) {
                $arrayListResultIds = $session->get('array_ids');
                $index = array_search($issueId, $arrayListResultIds);
            }
            $hasCreatePermission = $this->getRepository('yongo.project.project')->userHasPermission($projectId, Permission::PERM_CREATE_ISSUE, $session->get('user/id'));
            $hasEditPermission = $this->getRepository('yongo.project.project')->userHasPermission($projectId, Permission::PERM_EDIT_ISSUE, $session->get('user/id'));

            $hasDeletePermission = $this->getRepository('yongo.project.project')->userHasPermission($projectId, Permission::PERM_DELETE_ISSUE, $session->get('user/id'));
            $hasAssignPermission = $this->getRepository('yongo.project.project')->userHasPermission($projectId, Permission::PERM_ASSIGN_ISSUE, $session->get('user/id'));
            $hasAssignablePermission = $this->getRepository('yongo.project.project')->userHasPermission($projectId, Permission::PERM_ASSIGNABLE_USER, $session->get('user/id'));

            $hasLinkIssuePermission = $this->getRepository('yongo.project.project')->userHasPermission($projectId, Permission::PERM_LINK_ISSUE, $session->get('user/id'));
            $hasWorkOnIssuePermission = $this->getRepository('yongo.project.project')->userHasPermission($projectId, Permission::PERM_WORK_ON_ISSUE, $session->get('user/id'));
            $hasMoveIssuePermission = $this->getRepository('yongo.project.project')->userHasPermission($projectId, Permission::PERM_MOVE_ISSUE, $session->get('user/id'));
            $hasCreateAttachmentPermission = $this->getRepository('yongo.project.project')->userHasPermission($projectId, Permission::PERM_CREATE_ATTACHMENTS, $session->get('user/id'));

            $hasAddCommentsPermission = $this->getRepository('yongo.project.project')->userHasPermission($projectId, Permission::PERM_ADD_COMMENTS, $session->get('user/id'));

            $workflowUsed = $this->getRepository('yongo.project.project')->getWorkflowUsedForType($projectId, $issue[Field::FIELD_ISSUE_TYPE_CODE]);

            $step = $this->getRepository('yongo.workflow.workflow')->getStepByWorkflowIdAndStatusId($workflowUsed['id'], $issue[Field::FIELD_STATUS_CODE]);
            $stepProperties = $this->getRepository('yongo.workflow.workflow')->getStepProperties($step['id'], 'array');

            $issueEditableProperty = true;
            for ($i = 0; $i < count($stepProperties); $i++) {
                if ($stepProperties[$i]['name'] == StepProperty::ISSUE_EDITABLE && $stepProperties[$i]['value'] == 'false')
                    $issueEditableProperty = false;
            }

            $workflowActions = $this->getRepository('yongo.workflow.workflow')->getTransitionsForStepId($workflowUsed['id'], $step['id']);
            $screenData = $this->getRepository('yongo.project.project')->getScreenData($issueProject, $issue[Field::FIELD_ISSUE_TYPE_CODE], SystemOperation::OPERATION_CREATE, 'array');

            $childrenIssues = null;
            $parentIssue = null;
            if ($issue['parent_id'] == null) {
                $childrenIssues = UbirimiContainer::get()['repository']->get('yongo.issue.issue')->getByParameters(array('parent_id' => $issue['id']), $session->get('user/id'), null, $session->get('user/id'));
            } else {
                $parentIssue = $this->getRepository('yongo.issue.issue')->getByParameters(array('issue_id' => $issue['parent_id']), $session->get('user/id'), null, $session->get('user/id'));
            }

            $customFieldsData = CustomField::getCustomFieldsData($issue['id']);
            $customFieldsDataUserPickerMultipleUser = CustomField::getUserPickerData($issue['id']);

            $subTaskIssueTypes = $this->getRepository('yongo.project.project')->getSubTasksIssueTypes($projectId);

            $attachments = $this->getRepository('yongo.issue.attachment')->getByIssueId($issue['id'], true);
            $countAttachments = count($attachments);
            if ($countAttachments) {
                $hasDeleteOwnAttachmentsPermission = $this->getRepository('yongo.project.project')->userHasPermission($projectId, Permission::PERM_DELETE_OWN_ATTACHMENTS, $session->get('user/id'));
                $hasDeleteAllAttachmentsPermission = $this->getRepository('yongo.project.project')->userHasPermission($projectId, Permission::PERM_DELETE_OWN_ATTACHMENTS, $session->get('user/id'));
            }

            // get the worklogs
            $workLogs = WorkLog::getByIssueId($issueId);

            // get the watchers, if any
            $watchers = Watcher::getByIssueId($issueId);

            $linkedIssues = LinkType::getLinksByParentId($issueId);
            $linkIssueTypes = LinkType::getByClientId($session->get('client/id'));
            $issueLinkingFlag = $session->get('yongo/settings/issue_linking_flag');
            $hoursPerDay = $session->get('yongo/settings/time_tracking_hours_per_day');
            $daysPerWeek = $session->get('yongo/settings/time_tracking_days_per_week');

            $timeTrackingFlag = $session->get('yongo/settings/time_tracking_flag');

            $slasPrintData = null;
            if ($issueProject['help_desk_enabled_flag']) {
                $slasPrintData = $this->getRepository('yongo.issue.issue')->updateSLAValue($issue, $session->get('client/id'), $clientSettings);
            }

            // voters and watchers
            $hasViewVotersAndWatchersPermission = $this->getRepository('yongo.project.project')->userHasPermission($projectId, Permission::PERM_VIEW_VOTERS_AND_WATCHERS, $session->get('user/id'));
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