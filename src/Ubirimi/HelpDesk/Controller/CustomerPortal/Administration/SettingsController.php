<?php
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Project\Project;

    Util::checkUserIsLoggedInAndRedirect();
    $clientSettings = $session->get('client/settings');

    $projectId = $_GET['id'];
    $project = Project::getById($projectId);

    $menuSelectedCategory = 'help_desk';
    $menuProjectCategory = 'customer_portal';
    $sectionPageTitle = $clientSettings['title_name'] . ' / ' . SystemProduct::SYS_PRODUCT_HELP_DESK_NAME . ' / Customer Portal / Settings';

    require_once __DIR__ . '/../../../Resources/views/customer_portal/administration/Settings.php';