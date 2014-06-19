<?php

    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Permission\PermissionScheme;
    use Ubirimi\Yongo\Repository\Project\Project;

    Util::checkUserIsLoggedInAndRedirect();

    $projectId = $_GET['id'];
    $project = Project::getById($projectId);
    if ($project['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    if (isset($_POST['associate'])) {

        $permissionSchemeId = $_POST['perm_scheme'];

        Project::updatePermissionScheme($projectId, $permissionSchemeId);

        header('Location: /yongo/administration/project/permissions/' . $projectId);
    }

    $permissionSchemes = PermissionScheme::getByClientId($clientId);

    $menuSelectedCategory = 'project';

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Select Project Permission Scheme';

    require_once __DIR__ . '/../../../../Resources/views/administration/project/permission_scheme/Select.php';