<?php
    use Ubirimi\Repository\Client;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $session->set('selected_product_id', -1);

    $menuSelectedCategory = 'general_overview';
    $clientSettings = Client::getSettings($clientId);
    $client = Client::getById($clientId);

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / General Settings';

    require_once __DIR__ . '/../Resources/views/ViewGeneral.php';