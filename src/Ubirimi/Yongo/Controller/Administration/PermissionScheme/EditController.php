<?php
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Permission\Permission;
    use Ubirimi\Yongo\Repository\Permission\PermissionScheme;
    use Ubirimi\Yongo\Repository\Project\Project;

    Util::checkUserIsLoggedInAndRedirect();
    $Id = $_GET['id'];
    $backLink = isset($_GET['back']) ? $_GET['back'] : null;
    $projectId = isset($_GET['project_id']) ? $_GET['project_id'] : null;

    $permissionScheme = PermissionScheme::getMetaDataById($Id);

    if ($permissionScheme['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    if ($projectId) {
        $project = Project::getById($projectId);
        if ($project['client_id'] != $clientId) {
            header('Location: /general-settings/bad-link-access-denied');
            die();
        }
    }

    $permissionCategories = Permission::getCategories();
    $menuSelectedCategory = 'issue';
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Issue Permission Scheme';

    require_once __DIR__ . '/../../../Resources/views/administration/permission_scheme/Edit.php';