<?php
    use Ubirimi\Calendar\Repository\CalendarEvent;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_CALENDAR);
    $menuSelectedCategory = 'calendars';

    $query = $_GET['search_query'];
    $events = CalendarEvent::getByText($loggedInUserId, $query);

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_CALENDAR_NAME . ' / Search';

    require_once __DIR__ . '/../../Resources/views/event/Search.php';
