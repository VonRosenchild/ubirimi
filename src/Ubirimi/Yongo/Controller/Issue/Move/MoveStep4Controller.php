<?php
    use Ubirimi\LinkHelper;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\Issue;
    use Ubirimi\Yongo\Repository\Issue\Component;
    use Ubirimi\Yongo\Repository\Issue\Settings;
    use Ubirimi\Yongo\Repository\Issue\Version;
    use Ubirimi\Yongo\Repository\Project\Project;
    use Ubirimi\Yongo\Repository\Project\Component;
    use Ubirimi\Yongo\Repository\Project\Version;
    use Ubirimi\Repository\User\User;
    use Ubirimi\Yongo\Event\IssueEvent as Event;
    use Ubirimi\Event\LogEvent;
    use Ubirimi\Container\UbirimiContainer;
    use Ubirimi\Yongo\Event\YongoEvents;
    use Ubirimi\Event\UbirimiEvents;

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

    if (isset($_POST['move_issue_step_4'])) {
        $currentDate = Util::getServerCurrentDateTime();

        $oldIssueData = UbirimiContainer::getRepository('yongo.issue.issue')->getByParameters(array('issue_id' => $issueId), $loggedInUserId);
        $oldIssueData['component'] = Component::getByIssueIdAndProjectId($issueId, $projectId, 'array', 'name');
        if ($oldIssueData['component'] == null) {
            $oldIssueData['component'] = array();
        }
        $oldIssueData['affects_version'] = Version::getByIssueIdAndProjectId($issueId, $projectId, Issue::ISSUE_AFFECTED_VERSION_FLAG, 'array', 'name');
        if ($oldIssueData['affects_version'] == null) {
            $oldIssueData['affects_version'] = array();
        }
        $oldIssueData['fix_version'] = Version::getByIssueIdAndProjectId($issueId, $projectId, Issue::ISSUE_FIX_VERSION_FLAG, 'array', 'name');
        if ($oldIssueData['fix_version'] == null) {
            $oldIssueData['fix_version'] = array();
        }

        Component::deleteByIssueId($issueId);
        Version::deleteByIssueIdAndFlag($issueId, Issue::ISSUE_FIX_VERSION_FLAG);
        Version::deleteByIssueIdAndFlag($issueId, Issue::ISSUE_AFFECTED_VERSION_FLAG);

        if ($session->has('move_issue/new_assignee')) {
            Issue::updateAssigneeRaw($issueId, $session->get('move_issue/new_assignee'));
        }

        if (count($session->get('move_issue/new_component'))) {
            Issue::addComponentVersion($issueId, $session->get('move_issue/new_component'), 'issue_component');
        }

        if (count($session->get('move_issue/new_fix_version'))) {
            Issue::addComponentVersion($issueId, $session->get('move_issue/new_fix_version'), 'issue_version', Issue::ISSUE_FIX_VERSION_FLAG);
        }

        if (count($session->get('move_issue/new_affects_version'))) {
            Issue::addComponentVersion($issueId, $session->get('move_issue/new_affects_version'), 'issue_version', Issue::ISSUE_AFFECTED_VERSION_FLAG);
        }

        $newProjectId = $session->get('move_issue/new_project');

        // move the issue
        Issue::move($issueId, $newProjectId, $session->get('move_issue/new_type'), $session->get('move_issue/sub_task_new_issue_type'));

        $session->remove('move_issue');
        $newIssueData = UbirimiContainer::getRepository('yongo.issue.issue')->getByParameters(array('issue_id' => $issueId), $loggedInUserId);
        $fieldChanges = Issue::computeDifference($oldIssueData, $newIssueData, array(), array());
        Issue::updateHistory($issueId, $loggedInUserId, $fieldChanges, $currentDate);

        $issueEvent = new Event(null, null, Event::STATUS_UPDATE, array('oldIssueData' => $oldIssueData, 'fieldChanges' => $fieldChanges));
        $issueLogEvent = new LogEvent(SystemProduct::SYS_PRODUCT_YONGO, 'MOVE Yongo issue ' . $oldIssueData['project_code'] . '-' . $oldIssueData['nr']);

        UbirimiContainer::get()['dispatcher']->dispatch(YongoEvents::YONGO_ISSUE_EMAIL, $issueEvent);
        UbirimiContainer::get()['dispatcher']->dispatch(UbirimiEvents::LOG, $issueLogEvent);

        header('Location: ' . LinkHelper::getYongoIssueViewLinkJustHref($issueId));
        die();
    }

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Move Issue - ' . $issue['project_code'] . '-' . $issue['nr'] . ' ' . $issue['summary'];

    $previousData = $session->get('move_issue');
    $menuSelectedCategory = 'issue';

    $newStatusName = Settings::getById($session->get('move_issue/new_status'), 'status', 'name');

    $newProject = Project::getById($session->get('move_issue/new_project'));
    $newTypeName = Settings::getById($session->get('move_issue/new_type'), 'type', 'name');

    $issueComponents = Component::getByIssueIdAndProjectId($issue['id'], $projectId);
    $issueFixVersions = Version::getByIssueIdAndProjectId($issue['id'], $projectId, Issue::ISSUE_FIX_VERSION_FLAG);
    $issueAffectedVersions = Version::getByIssueIdAndProjectId($issue['id'], $projectId, Issue::ISSUE_AFFECTED_VERSION_FLAG);

    $newIssueComponents = null;
    $newIssueFixVersions = null;
    $newIssueAffectsVersions = null;

    if (count($session->get('move_issue/new_component'))) {
        $newIssueComponents = Component::getByIds($session->get('move_issue/new_component'));
    }

    if (count($session->get('move_issue/new_fix_version'))) {
        $newIssueFixVersions = Version::getByIds($session->get('move_issue/new_fix_version'));
    }

    if (count($session->get('move_issue/new_affects_version'))) {
        $newIssueAffectsVersions = Version::getByIds($session->get('move_issue/new_affects_version'));
    }

    $newUserAssignee = User::getById($session->get('move_issue/new_assignee'));

    require_once __DIR__ . '/../../../Resources/views/issue/move/MoveStep4.php';