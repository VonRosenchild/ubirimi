<?php
    use Ubirimi\Repository\Client;
    use Ubirimi\Repository\User\User;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Permission\GlobalPermission;
    use Ubirimi\Yongo\Repository\Permission\Permission;
    use Ubirimi\Yongo\Repository\Project\Project;

    if (Util::checkUserIsLoggedIn()) {
        $selectedProjectId = $session->get('selected_project_id');
    } else {
        $httpHOST = Util::getHttpHost();
        $clientId = Client::getByBaseURL($httpHOST, 'array', 'id');
        $loggedInUserId = null;
        $selectedProjectId = null;
    }

    if ($session->get('selected_product_id') == SystemProduct::SYS_PRODUCT_YONGO) {
        $urlPrefix = '/yongo/project/';
        $projectsMenu = Client::getProjectsByPermission($clientId, $loggedInUserId, Permission::PERM_BROWSE_PROJECTS, 'array');
    } else {
        $urlPrefix = '/helpdesk/customer-portal/project/';
        $projectsMenu = Client::getProjects($clientId, 'array', null, 1);
    }

    $selectedProjectMenu = null;
    if ($selectedProjectId)
        $selectedProjectMenu = Project::getById($selectedProjectId);

    $hasGlobalAdministrationPermission = User::hasGlobalPermission($clientId,
                                                                   $loggedInUserId,
                                                                   GlobalPermission::GLOBAL_PERMISSION_YONGO_ADMINISTRATORS);
    $hasGlobalSystemAdministrationPermission = User::hasGlobalPermission($clientId,
                                                                         $loggedInUserId,
                                                                         GlobalPermission::GLOBAL_PERMISSION_YONGO_SYSTEM_ADMINISTRATORS);

    require_once __DIR__ . '/../../Resources/views/menu/Projects.php';