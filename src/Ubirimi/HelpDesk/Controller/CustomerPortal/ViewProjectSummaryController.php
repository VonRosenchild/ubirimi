<?php
    use Ubirimi\Repository\Client;
    use Ubirimi\Repository\User\User;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Permission\GlobalPermission;
    use Ubirimi\Yongo\Repository\Permission\Permission;
    use Ubirimi\Yongo\Repository\Project\Project;

    Util::checkUserIsLoggedInAndRedirect();

    $projectId = $_GET['id'];

    $project = Project::getById($projectId);
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_HELP_DESK_NAME . ' / ' . $project['name'];

    $session->set('selected_project_id', $projectId);
    $menuSelectedCategory = 'project';

    $menuProjectCategory = 'summary';

    require_once __DIR__ . '/../../Resources/views/customer_portal/ViewProjectSummary.php';