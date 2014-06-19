<?php
    use Ubirimi\Repository\Client;
    use Ubirimi\Repository\Documentador\Entity;
    use Ubirimi\Repository\Documentador\Space;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    $loggedInUserId = null;

    if (Util::checkUserIsLoggedIn()) {
        $loggedInUserId = $session->get('user/id');
        $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_DOCUMENTADOR);
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_DOCUMENTADOR_NAME. ' / Dashboard';

    } else {
        $httpHOST = Util::getHttpHost();
        $clientId = Client::getByBaseURL($httpHOST, 'array', 'id');
        $sectionPageTitle = SystemProduct::SYS_PRODUCT_DOCUMENTADOR_NAME. ' / Dashboard';
    }

    $type = null;
    if (isset($_GET['type'])) {
        $type = $_GET['type'];
    }

    $menuSelectedCategory = 'documentator';

    if ($type == 'spaces') {
        if (Util::checkUserIsLoggedIn())
            $spaces = Space::getByClientId($clientId, 1);
        else
            $spaces = Space::getByClientIdAndAnonymous($clientId);
    } else if ($type == 'pages') {
        $pages = Entity::getFavouritePagesByClientIdAndUserId($clientId, $loggedInUserId);
    }

    require_once __DIR__ . '/../Resources/views/Dashboard.php';