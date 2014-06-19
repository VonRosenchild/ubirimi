<?php
    use Ubirimi\Repository\Client;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $menuSelectedCategory = 'home';
    $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_HELP_DESK);

    $projectsForBrowsing = Client::getProjects($clientId, null, null, true);

    if ($projectsForBrowsing) {
        $projectIdsAndNames = Util::getAsArray($projectsForBrowsing, array('id', 'name'));
        $projectsForBrowsing->data_seek(0);
        $projectIds = Util::getAsArray($projectsForBrowsing, array('id'));
    }

    require_once __DIR__ . '/../../Resources/views/customer_portal/Dashboard.php';