<?php
    use Ubirimi\Repository\Client;
    use Ubirimi\Repository\Documentador\Entity;
    use Ubirimi\Repository\Group\Group;
    use Ubirimi\Repository\User\User;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    if (Util::checkUserIsLoggedIn()) {

        $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_DOCUMENTADOR);
    } else {
        $httpHOST = Util::getHttpHost();
        $clientId = Client::getByBaseURL($httpHOST, 'array', 'id');
        $loggedInUserId = null;

        $settingsDocumentator = Client::getDocumentatorSettings($clientId);

        $documentatorUseAnonymous = $settingsDocumentator['anonymous_use_flag'];
        $documentatorAnonymousViewUserProfiles = $settingsDocumentator['anonymous_view_user_profile_flag'];

        if (!($documentatorUseAnonymous && $documentatorAnonymousViewUserProfiles)) {
            Util::signOutAndRedirect();
            die();
        }
    }

    $userId = $_GET['id'];
    $user = User::getById($userId);

    if ($user['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    $menuSelectedCategory = 'documentator';
    $groups = Group::getByUserIdAndProductId($userId, SystemProduct::SYS_PRODUCT_DOCUMENTADOR);

    $pages = Entity::getFavouritePagesByClientIdAndUserId($clientId, $userId);
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_DOCUMENTADOR_NAME. ' / ' . $user['first_name'] . ' ' . $user['last_name'] . ' / Favourites';

    require_once __DIR__ . '/../../Resources/views/user/Favourites.php';