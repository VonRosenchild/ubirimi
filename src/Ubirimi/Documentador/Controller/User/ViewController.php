<?php

    use Ubirimi\Repository\Group\Group;
    use Ubirimi\Repository\User\User;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    if (Util::checkUserIsLoggedIn()) {

        $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_DOCUMENTADOR);
    } else {
        $httpHOST = Util::getHttpHost();
        $clientId = $this->getRepository('ubirimi.general.client')->getByBaseURL($httpHOST, 'array', 'id');
        $loggedInUserId = null;

        $settingsDocumentator = $this->getRepository('ubirimi.general.client')->getDocumentatorSettings($clientId);

        $documentatorUseAnonymous = $settingsDocumentator['anonymous_use_flag'];
        $documentatorAnonymousViewUserProfiles = $settingsDocumentator['anonymous_view_user_profile_flag'];

        if (!($documentatorUseAnonymous && $documentatorAnonymousViewUserProfiles)) {
            Util::signOutAndRedirect();
            die();
        }
    }

    $userId = $_GET['id'];
    $user = $this->getRepository('ubirimi.user.user')->getById($userId);
    if ($user['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    $menuSelectedCategory = 'documentator';
    $groups = $this->getRepository('ubirimi.user.group')->getByUserIdAndProductId($userId, SystemProduct::SYS_PRODUCT_DOCUMENTADOR);

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_DOCUMENTADOR_NAME. ' / ' . $user['first_name'] . ' ' . $user['last_name'] . ' / Summary';

    require_once __DIR__ . '/../../Resources/views/user/View.php';