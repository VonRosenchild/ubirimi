<?php
    use Ubirimi\Repository\Client;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueFilter;
    use Ubirimi\Yongo\Repository\Permission\Permission;

    if (Util::checkUserIsLoggedIn()) {

    } else {
        $httpHOST = Util::getHttpHost();
        $clientId = Client::getByBaseURL($httpHOST, 'array', 'id');
        $loggedInUserId = null;
    }
    $projectsMenu = Client::getProjectsByPermission($clientId, $loggedInUserId, Permission::PERM_BROWSE_PROJECTS, 'array');

    $projectsForBrowsing = array();
    for ($i = 0; $i < count($projectsMenu); $i++) {
        $projectsForBrowsing[$i] = $projectsMenu[$i]['id'];
    }

    $customFilters = IssueFilter::getAllByUser($loggedInUserId);

    require_once __DIR__ . '/../../Resources/views/menu/Filters.php';