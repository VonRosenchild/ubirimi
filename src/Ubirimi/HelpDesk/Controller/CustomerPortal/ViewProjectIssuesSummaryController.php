<?php
    use Ubirimi\Repository\Client;
    use Ubirimi\Repository\User\User;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\Issue;
    use Ubirimi\Yongo\Repository\Permission\GlobalPermission;
    use Ubirimi\Yongo\Repository\Permission\Permission;
    use Ubirimi\Yongo\Repository\Project\Project;

    Util::checkUserIsLoggedInAndRedirect();

    $clientSettings = $session->get('client/settings');

    $projectId = $_GET['id'];
    $project = Project::getById($projectId);

    if ($project['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    $issueQueryParameters = array('project' => $projectId, 'resolution' => array(-2), 'helpdesk_flag' => 1);
    $issues = Issue::getByParameters($issueQueryParameters, $loggedInUserId, null, $loggedInUserId);

    $count = 0;
    $statsPriority = array();
    $statsType = array();
    $statsStatus = array();
    $statsAssignee = array();

    if ($issues) {
        $count = $issues->num_rows;
        // group them by priority
        while ($issues && $issue = $issues->fetch_array(MYSQLI_ASSOC)) {
            if (!isset($statsPriority[$issue['priority']])) {
                $statsPriority[$issue['priority']] = array($issue['priority_name'] => 0);
            }
            $statsPriority[$issue['priority']][$issue['priority_name']]++;
        }

        // group them by type
        $issues->data_seek(0);
        $statsType = array();
        while ($issues && $issue = $issues->fetch_array(MYSQLI_ASSOC)) {
            if (!isset($statsType[$issue['type']])) {
                $statsType[$issue['type']] = array($issue['type_name'] => 0);
            }
            $statsType[$issue['type']][$issue['type_name']]++;
        }

        // group them by status
        $issues->data_seek(0);
        $statsStatus = array();
        while ($issues && $issue = $issues->fetch_array(MYSQLI_ASSOC)) {
            if (!isset($statsStatus[$issue['status']])) {
                $statsStatus[$issue['status']] = array($issue['status_name'] => 0);
            }
            $statsStatus[$issue['status']][$issue['status_name']]++;
        }

        // group them by assignee
        $issues->data_seek(0);
        $statsAssignee = array();
        while ($issues && $issue = $issues->fetch_array(MYSQLI_ASSOC)) {
            if (!isset($statsAssignee[$issue['assignee']])) {
                $userName = $issue['ua_first_name'] . ' ' . $issue['ua_last_name'];
                $statsAssignee[$issue['assignee']] = array($userName => 0);
            }
            $userName = $issue['ua_first_name'] . ' ' . $issue['ua_last_name'];
            $statsAssignee[$issue['assignee']][$userName]++;
        }
    }

    $issueQueryParameters = array('project' => $projectId, 'resolution' => array(-2), 'component' => -1, 'helpdesk_flag' => 1);
    $issues = Issue::getByParameters($issueQueryParameters, $loggedInUserId, null, $loggedInUserId);
    $countUnresolvedWithoutComponent = 0;
    if ($issues) {
        $countUnresolvedWithoutComponent = $issues->num_rows;
    }

    $components = Project::getComponents($projectId);
    $statsComponent = array();
    while ($components && $component = $components->fetch_array(MYSQLI_ASSOC)) {
        $issueQueryParameters = array('project' => $projectId, 'component' => $component['id'], 'helpdesk_flag' => 1);
        $issues = Issue::getByParameters($issueQueryParameters, $loggedInUserId, null, $loggedInUserId);
        if ($issues)
            $statsComponent[$component['name']] = array($component['id'], $issues->num_rows);
    }

    $menuSelectedCategory = 'project';
    $menuProjectCategory = 'issues';
    $sectionPageTitle = $clientSettings['title_name'] . ' / ' . SystemProduct::SYS_PRODUCT_HELP_DESK . ' / ' . $project['name'] . ' / Issue Summary';

    require_once __DIR__ . '/../../Resources/views/customer_portal/ViewProjectIssuesSummary.php';