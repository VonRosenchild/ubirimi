<?php
    use Ubirimi\Repository\Client;
    use Ubirimi\Repository\Documentador\Entity;
    use Ubirimi\Repository\Documentador\Space;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;


    if (Util::checkUserIsLoggedIn()) {

        $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_DOCUMENTADOR);
    } else {
        $httpHOST = Util::getHttpHost();
        $clientId = Client::getByBaseURL($httpHOST, 'array', 'id');
        $loggedInUserId = null;
    }

    $spaceId = $_GET['space_id'];
    $space = Space::getById($spaceId);

    $menuSelectedCategory = 'documentator';
    $space = Space::getById($spaceId);

    if ($space['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    $spaceHasAnonymousAccess = Space::hasAnonymousAccess($spaceId);
    $pages = Entity::getAllBySpaceId($spaceId, 0);
    $homePage = Entity::getById($space['home_entity_id']);

    if ($homePage['in_trash_flag']) {
        $homePage = null;
    }

    require_once __DIR__ . '/../Resources/views/ListPages.php';