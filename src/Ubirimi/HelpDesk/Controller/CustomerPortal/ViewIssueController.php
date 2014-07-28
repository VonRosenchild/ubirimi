<?php
    use Ubirimi\Repository\Client;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Field\Field;
    use Ubirimi\Yongo\Repository\Issue\Issue;
    use Ubirimi\Yongo\Repository\Issue\IssueAttachment;
    use Ubirimi\Yongo\Repository\Issue\IssueComponent;
    use Ubirimi\Yongo\Repository\Issue\IssueCustomField;
    use Ubirimi\Yongo\Repository\Issue\IssueLinkType;
    use Ubirimi\Yongo\Repository\Issue\IssueVersion;
    use Ubirimi\Yongo\Repository\Issue\SystemOperation;
    use Ubirimi\Yongo\Repository\Permission\Permission;
    use Ubirimi\Yongo\Repository\Project\Project;
    use Ubirimi\Yongo\Repository\Workflow\Workflow;
    use Ubirimi\Yongo\Repository\Workflow\WorkflowStepProperty;
    use Ubirimi\Yongo\Repository\Issue\IssueWatcher;

    $issueId = $_GET['id'];

    Util::checkUserIsLoggedInAndRedirect();

    $issue = Issue::getById($issueId, $loggedInUserId);
    $issueId = $issue['id'];
    $projectId = $issue['issue_project_id'];
    $clientSettings = $session->get('client/settings');

    $sectionPageTitle = $clientSettings['title_name'] . ' / ' . SystemProduct::SYS_PRODUCT_HELP_DESK_NAME . ' / ' . $issue['project_code'] . '-' . $issue['nr'] . ' ' . $issue['summary'];

    $session->set('selected_project_id', $projectId);
    $issueProject = Project::getById($projectId);

    /* before going further, check to is if the issue id a valid id -- start */
    $issueValid = true;
    if (!$issue || $clientId != $issueProject['client_id']) {
        $issueValid = false;
    }

    /* before going further, check to is if the issue id a valid id -- end */

    $components = IssueComponent::getByIssueIdAndProjectId($issueId, $projectId);
    $versionsAffected = IssueVersion::getByIssueIdAndProjectId($issueId, $projectId, Issue::ISSUE_AFFECTED_VERSION_FLAG);
    $versionsTargeted = IssueVersion::getByIssueIdAndProjectId($issueId, $projectId, Issue::ISSUE_FIX_VERSION_FLAG);

    $arrayListResultIds = null;
    if ($session->has('array_ids')) {
        $arrayListResultIds = $session->get('array_ids');
        $index = array_search($issueId, $arrayListResultIds);
    }

    $workflowUsed = Project::getWorkflowUsedForType($projectId, $issue[Field::FIELD_ISSUE_TYPE_CODE]);

    $step = Workflow::getStepByWorkflowIdAndStatusId($workflowUsed['id'], $issue[Field::FIELD_STATUS_CODE]);
    $stepProperties = Workflow::getStepProperties($step['id'], 'array');

    if ($issueValid) {

        $workflowActions = Workflow::getTransitionsForStepId($workflowUsed['id'], $step['id']);
        $screenData = Project::getScreenData($issueProject, $issue[Field::FIELD_ISSUE_TYPE_CODE], SystemOperation::OPERATION_CREATE, 'array');

        $customFieldsData = IssueCustomField::getCustomFieldsData($issue['id']);

        $attachments = IssueAttachment::getByIssueId($issue['id'], true);
        $countAttachments = count($attachments);

        $atLeastOneSLA = false;
        $slasPrintData = Issue::updateSLAValue($issue, $clientId, $clientSettings);

        foreach ($slasPrintData as $slaData) {
            if ($slaData['goal']) {
                $atLeastOneSLA = true;
                break;
            }
        }
        $watchers = IssueWatcher::getByIssueId($issueId);
        $timeTrackingFlag = $session->get('yongo/settings/time_tracking_flag');

        $customFieldsData = IssueCustomField::getCustomFieldsData($issue['id']);
        $customFieldsDataUserPickerMultipleUser = IssueCustomField::getUserPickerData($issue['id']);
    }

    $menuSelectedCategory = 'issue';
    $hasEditPermission = true;
    $issueEditableProperty = true;
    $hasAssignPermission = false;
    $hasAddCommentsPermission = true;
    $hasDeletePermission = true;
    $childrenIssues = null;
    $linkedIssues = null;
    $linkIssueTypes = null;
    $hasCreateAttachmentPermission = true;
    $hasDeleteAllAttachmentsPermission = false;
    $hasDeleteOwnAttachmentsPermission = true;
    $hasLinkIssuePermission = false;
    $parentIssue = null;

    $menuSelectedCategory = 'home';
    $showWorkflowMenu = false;

    require_once __DIR__ . '/../../Resources/views/customer_portal/ViewIssue.php';