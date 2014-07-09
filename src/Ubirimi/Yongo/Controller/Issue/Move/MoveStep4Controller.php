<?php
    use Ubirimi\LinkHelper;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\Issue;
    use Ubirimi\Yongo\Repository\Issue\IssueComponent;
    use Ubirimi\Yongo\Repository\Issue\IssueSettings;
    use Ubirimi\Yongo\Repository\Issue\IssueVersion;
    use Ubirimi\Yongo\Repository\Project\Project;
    use Ubirimi\Yongo\Repository\Project\ProjectComponent;
    use Ubirimi\Yongo\Repository\Project\ProjectVersion;

    Util::checkUserIsLoggedInAndRedirect();

    $issueId = $_GET['id'];
    $issueQueryParameters = array('issue_id' => $issueId);
    $issue = Issue::getByParameters($issueQueryParameters, $loggedInUserId);
    $projectId = $issue['issue_project_id'];
    $issueProject = Project::getById($projectId);

    // before going further, check to is if the issue project belongs to the client
    if ($clientId != $issueProject['client_id']) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_YONGO);

    if (isset($_POST['move_issue_step_4'])) {

        IssueComponent::deleteByIssueId($issueId);
        IssueVersion::deleteByIssueId($issueId, Issue::ISSUE_FIX_VERSION_FLAG);
        IssueVersion::deleteByIssueId($issueId, Issue::ISSUE_AFFECTED_VERSION_FLAG);

        if (count($session->get('move_issue/new_component'))) {
            Issue::addComponentVersion($issueId, $session->get('move_issue/new_component'), 'issue_component');
        }

        if (count($session->get('move_issue/new_fix_version'))) {
            Issue::addComponentVersion($issueId, $session->get('move_issue/new_fix_version'), 'issue_version', Issue::ISSUE_FIX_VERSION_FLAG);
        }

        if (count($session->get('move_issue/new_affects_version'))) {
            Issue::addComponentVersion($issueId, $session->get('move_issue/new_affects_version'), 'issue_version', Issue::ISSUE_AFFECTED_VERSION_FLAG);
        }

        // move the issue
        Issue::move($issueId, $session->get('move_issue/new_project'), $session->get('move_issue/new_type'), $session->get('move_issue/sub_task_new_issue_type'));

        $session->remove('move_issue');

        header('Location: ' . LinkHelper::getYongoIssueViewLinkJustHref($issueId));
        die();
    }

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Move Issue - ' . $issue['project_code'] . '-' . $issue['nr'] . ' ' . $issue['summary'];

    $previousData = $session->get('move_issue');
    $menuSelectedCategory = 'issue';

    $newStatusName = IssueSettings::getById($session->get('move_issue/new_status'), 'status', 'name');

    $newProject = Project::getById($session->get('move_issue/new_project'));
    $newTypeName = IssueSettings::getById($session->get('move_issue/new_type'), 'type', 'name');

    $issueComponents = IssueComponent::getByIssueIdAndProjectId($issue['id'], $projectId);
    $issueFixVersions = IssueVersion::getByIssueIdAndProjectId($issue['id'], $projectId, Issue::ISSUE_FIX_VERSION_FLAG);
    $issueAffectedVersions = IssueVersion::getByIssueIdAndProjectId($issue['id'], $projectId, Issue::ISSUE_AFFECTED_VERSION_FLAG);

    $newIssueComponents = null;
    $newIssueFixVersions = null;
    $newIssueAffectsVersions = null;

    if (count($session->get('move_issue/new_component'))) {
        $newIssueComponents = ProjectComponent::getByIds($session->get('move_issue/new_component'));
    }

    if (count($session->get('move_issue/new_fix_version'))) {
        $newIssueFixVersions = ProjectVersion::getByIds($session->get('move_issue/new_fix_version'));
    }

    if (count($session->get('move_issue/new_affects_version'))) {
        $newIssueAffectsVersions = ProjectVersion::getByIds($session->get('move_issue/new_affects_version'));
    }

    require_once __DIR__ . '/../../../Resources/views/issue/move/MoveStep4.php';