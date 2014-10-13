<?php
    use Ubirimi\Repository\Client;
    use Ubirimi\Repository\Documentador\Space;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    if (Util::checkUserIsLoggedIn()) {

        $spaces = Space::getByClientId($clientId);
        $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_DOCUMENTADOR);
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_DOCUMENTADOR_NAME. ' / Spaces';
        $clientSettings = $session->get('client/settings');
    } else {
        $httpHOST = Util::getHttpHost();
        $clientId = $this->getRepository('ubirimi.general.client')->getByBaseURL($httpHOST, 'array', 'id');
        $clientSettings = $this->getRepository('ubirimi.general.client')->getSettings($clientId);
        $spaces = Space::getByClientIdAndAnonymous($clientId);
        $loggedInUserId = null;
        $sectionPageTitle = SystemProduct::SYS_PRODUCT_DOCUMENTADOR_NAME. ' / Spaces';
    }

    $menuSelectedCategory = 'documentator';

    require_once __DIR__ . '/../../Resources/views/UserSpaces.php';