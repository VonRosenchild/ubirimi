<?php
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Project\Project;

    Util::checkUserIsLoggedInAndRedirect();

    $hasYongoGlobalAdministrationPermission = $session->get('user/yongo/is_global_administrator');
    $hasYongoGlobalSystemAdministrationPermission = $session->get('user/yongo/is_global_system_administrator');
    $hasYongoAdministerProjectsPermission = $session->get('user/yongo/is_global_project_administrator');

    $menuSelectedCategory = 'administration';
    $last5Projects = Project::getLast5ByClientId($clientId);
    $countProjects = Project::getCount($clientId);

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Administration';

    require_once __DIR__ . '/../../Resources/views/administration/Index.php';