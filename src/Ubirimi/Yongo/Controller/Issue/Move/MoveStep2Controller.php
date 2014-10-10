<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\SystemProduct;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Issue\Component;
use Ubirimi\Yongo\Repository\Issue\IssueSettings;
use Ubirimi\Yongo\Repository\Issue\IssueVersion;
use Ubirimi\Yongo\Repository\Project\Project;
use Ubirimi\Yongo\Repository\Workflow\Workflow;

Util::checkUserIsLoggedInAndRedirect();

$issueId = $_GET['id'];
$issueQueryParameters = array('issue_id' => $issueId);
$issue = UbirimiContainer::getRepository('yongo.issue.issue')->getByParameters($issueQueryParameters, $loggedInUserId);
$projectId = $issue['issue_project_id'];
$issueProject = Project::getById($projectId);

// before going further, check to is if the issue project belongs to the client
if ($clientId != $issueProject['client_id']) {
    header('Location: /general-settings/bad-link-access-denied');
    die();
}

$session->set('selected_product_id', SystemProduct::SYS_PRODUCT_YONGO);

if (isset($_POST['move_issue_step_2'])) {
    $newStatusId = Util::cleanRegularInputField($_POST['move_to_status']);

    $session->set('move_issue/new_status', $newStatusId);

    // check if step 3 is necessary

    $issueComponents = Component::getByIssueIdAndProjectId($issue['id'], $projectId);
    $issueFixVersions = IssueVersion::getByIssueIdAndProjectId($issue['id'], $projectId, Issue::ISSUE_FIX_VERSION_FLAG);
    $issueAffectedVersions = IssueVersion::getByIssueIdAndProjectId($issue['id'], $projectId, Issue::ISSUE_AFFECTED_VERSION_FLAG);

    if ($issueComponents || $issueFixVersions || $issueAffectedVersions) {
        header('Location: /yongo/issue/move/fields/' . $issueId);
    } else {
        header('Location: /yongo/issue/move/confirmation/' . $issueId);
    }

    die();
}

$sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Move Issue - ' . $issue['project_code'] . '-' . $issue['nr'] . ' ' . $issue['summary'];
$currentWorkflow = Project::getWorkflowUsedForType($projectId, $issue['type']);

$previousData = $session->get('move_issue');
$newWorkflow = Project::getWorkflowUsedForType($previousData['new_project'], $previousData['new_type']);
$newStatuses = Workflow::getLinkedStatuses($newWorkflow['id']);
$menuSelectedCategory = 'issue';

$newProject = Project::getById($session->get('move_issue/new_project'));
$newProjectName = $newProject['name'];
$newTypeName = IssueSettings::getById($session->get('move_issue/new_type'), 'type', 'name');

require_once __DIR__ . '/../../../Resources/views/issue/move/MoveStep2.php';