<?php
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();
    $session->set('selected_product_id', -1);
    $menuSelectedCategory = 'general_home';

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / General Settings / Manage Applications';

    $productsArray = $session->get('client/products');

    require_once __DIR__ . '/../Resources/views/ManageApplications.php';
