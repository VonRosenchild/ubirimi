<?php
    use Ubirimi\Repository\Client;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $clientSettings = $session->get('client/settings');
    $projects = Client::getProjects($clientId, null, null, true);

    $menuSelectedCategory = 'project';

    $sectionPageTitle = $clientSettings['title_name'] . ' / ' . SystemProduct::SYS_PRODUCT_HELP_DESK_NAME . ' / Projects';

    require_once __DIR__ . '/../../Resources/views/customer_portal/ListProject.php';