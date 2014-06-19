<?php
    use Ubirimi\Repository\Client;
    use Ubirimi\Util;

    if (Util::checkUserIsLoggedIn()) {
        $clientSettings = $session->get('client/settings');

    } else {
        $clientId = Client::getClientIdAnonymous();
        $clientSettings = Client::getSettings($clientId);
        $loggedInUserId = null;
    }

    $session->set('selected_product_id', -2);

    $menuSelectedCategory = 'ubirimi_about';

    $sectionPageTitle = $clientSettings['title_name'] . ' / About Ubirimi';

    require_once __DIR__ . '/../Resources/views/About.php';