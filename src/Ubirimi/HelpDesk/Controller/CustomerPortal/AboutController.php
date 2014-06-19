<?php
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $clientSettings = $session->get('client/settings');

    $session->set('selected_product_id', -2);

    $menuSelectedCategory = 'ubirimi_about';

    $sectionPageTitle = $clientSettings['title_name'] . ' / About Customer Portal';

    require_once __DIR__ . '/../../Resources/views/customer_portal/About.php';