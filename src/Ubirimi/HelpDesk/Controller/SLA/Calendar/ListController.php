<?php
    use Ubirimi\Repository\HelpDesk\SLA;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();
    $clientSettings = $session->get('client/settings');

    $menuSelectedCategory = 'help_desk';
    $menuProjectCategory = 'sla';
    $sectionPageTitle = $clientSettings['title_name'] . ' / ' . SystemProduct::SYS_PRODUCT_HELP_DESK_NAME . ' / SLA Calendars';

    $calendars = SLA::getCalendars($clientId);

    require_once __DIR__ . '/../../../Resources/views/sla/calendar/List.php';