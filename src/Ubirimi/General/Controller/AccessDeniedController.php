<?php
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $session->set('selected_product_id', -1);
    $menuSelectedCategory = 'general_home';

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / Something is wrong...';

    require_once __DIR__ . '/../Resources/views/AccessDenied.php';