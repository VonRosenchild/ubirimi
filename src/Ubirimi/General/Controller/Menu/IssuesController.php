<?php

    use Ubirimi\Repository\Client;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Permission\Permission;
    use Ubirimi\Yongo\Repository\Project\Project;

    if (Util::checkUserIsLoggedIn()) {

    } else {
        $httpHOST = Util::getHttpHost();
        $clientId = Client::getByBaseURL($httpHOST, 'array', 'id');
        $loggedInUserId = null;
    }
    $projectsMenu = Client::getProjectsByPermission($clientId, $loggedInUserId, Permission::PERM_BROWSE_PROJECTS, 'array');

    $projectsForBrowsing = array();
    for ($i = 0; $i < count($projectsMenu); $i++)
        $projectsForBrowsing[$i] = $projectsMenu[$i]['id'];

    $hasCreateIssuePermission = false;
    if (count($projectsForBrowsing)) {
        $hasCreateIssuePermission = Project::userHasPermission($projectsForBrowsing, Permission::PERM_CREATE_ISSUE, $loggedInUserId);
    }

    $recentIssues = $session->get('yongo/recent_issues');

    require_once __DIR__ . '/../../Resources/views/menu/Issues.php';
