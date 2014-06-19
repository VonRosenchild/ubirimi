<?php
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueEvent;

    Util::checkUserIsLoggedInAndRedirect();
    $events = IssueEvent::getByClient($clientId);
    $menuSelectedCategory = 'system';

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Events';

    require_once __DIR__ . '/../../../Resources/views/administration/event/list.php';