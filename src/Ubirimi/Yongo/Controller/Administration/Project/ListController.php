<?php
    use Ubirimi\Repository\Client;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Permission\Permission;
    use Ubirimi\Yongo\Repository\Project\ProjectCategory;

    Util::checkUserIsLoggedInAndRedirect();

    $hasGlobalAdministrationPermission = $session->get('user/yongo/is_global_administrator');
    $hasGlobalSystemAdministrationPermission = $session->get('user/yongo/is_global_system_administrator');
    $hasAdministerProjectsPermission = $session->get('user/yongo/is_global_project_administrator');

    $accessToPage = false;
    if ($hasAdministerProjectsPermission || $hasGlobalAdministrationPermission || $hasGlobalSystemAdministrationPermission) {
        $accessToPage = true;
    }

    if ($hasGlobalAdministrationPermission && $hasGlobalSystemAdministrationPermission) {
        $projects = Client::getProjects($clientId, 'array');
    } else if ($hasAdministerProjectsPermission) {
        $projects = Client::getProjectsByPermission($clientId, $loggedInUserId, Permission::PERM_ADMINISTER_PROJECTS, 'array');
    }

    $projectCategories = ProjectCategory::getAll($clientId);
    $menuSelectedCategory = 'project';

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Projects';

    $includeCheckbox = true;
    require_once __DIR__ . '/../../../Resources/views/administration/project/List.php';