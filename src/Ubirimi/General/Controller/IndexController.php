<?php
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();
    $session->set('selected_product_id', -1);
    $menuSelectedCategory = 'general_home';

    $today = date('Y-m-d');
    $lastWeekToday = date('Y-m-d', strtotime('-1 week'));

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / General Settings / Home';

    require_once __DIR__ . '/../Resources/views/Index.php';
