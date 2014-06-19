<?php
    use Ubirimi\Repository\Client;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();
    $clientSettings = $session->get('client/settings');
    $projects = Client::getProjects($clientId, null, null, true);

    $menuSelectedCategory = 'help_desk';

    $sectionPageTitle = $clientSettings['title_name'] . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Help Desks';

    require_once __DIR__ . '/../Resources/views/List.php';