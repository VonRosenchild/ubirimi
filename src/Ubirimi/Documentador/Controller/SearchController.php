<?php
    use Ubirimi\Repository\Client;
    use Ubirimi\Repository\Documentador\Space;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    if (Util::checkUserIsLoggedIn()) {

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_DOCUMENTADOR_NAME. ' / Search';
    } else {
        $httpHOST = Util::getHttpHost();
        $clientId = Client::getByBaseURL($httpHOST, 'array', 'id');
        $loggedInUserId = null;

        $sectionPageTitle = SystemProduct::SYS_PRODUCT_DOCUMENTADOR_NAME. ' / Search';
    }

    if (isset($_POST['search'])) {
        $searchQuery = $_POST['keyword'];

        header('Location: /documentador/search?search_query=' . $searchQuery);
    }

    $searchQuery = $_GET['search_query'];
    $menuSelectedCategory = 'documentator';

    $pages = Space::searchForPages($clientId, $searchQuery);

    require_once __DIR__ . '/../Resources/views/Search.php';