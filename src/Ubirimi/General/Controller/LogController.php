<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $session->set('selected_product_id', -1);
    $menuSelectedCategory = 'general_overview';

    $from = $_GET['from'];
    $to = $_GET['to'];

    $logs = Log::getByClientIdAndInterval($clientId, $from, $to);

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / General Settings / Logs';

    require_once __DIR__ . '/../Resources/views/Log.php';