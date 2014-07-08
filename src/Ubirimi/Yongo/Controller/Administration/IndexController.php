<?php
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Project\Project;
    use Ubirimi\Repository\Client;
    use Ubirimi\Yongo\Repository\Permission\Permission;

    Util::checkUserIsLoggedInAndRedirect();

    $hasYongoGlobalAdministrationPermission = $session->get('user/yongo/is_global_administrator');
    $hasYongoGlobalSystemAdministrationPermission = $session->get('user/yongo/is_global_system_administrator');
    $hasYongoAdministerProjectsPermission = $session->get('user/yongo/is_global_project_administrator');

    $menuSelectedCategory = 'administration';

    if ($hasYongoGlobalAdministrationPermission && $hasYongoGlobalSystemAdministrationPermission) {
        $projects = Client::getProjects($clientId, 'array');
        $last5Projects = Project::getLast5ByClientId($clientId);
        $countProjects = Project::getCount($clientId);
    } else if ($hasYongoAdministerProjectsPermission) {
        $projects = Client::getProjectsByPermission($clientId, $loggedInUserId, Permission::PERM_ADMINISTER_PROJECTS, 'array');
        $countProjects = count($projects);
        $last5Projects = null;
    }

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Administration';

    require_once __DIR__ . '/../../Resources/views/administration/Index.php';