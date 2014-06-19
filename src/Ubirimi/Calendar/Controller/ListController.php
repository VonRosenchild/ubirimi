<?php
    use Ubirimi\Calendar\Repository\Calendar;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_CALENDAR);
    $menuSelectedCategory = 'calendars';
    $calendars = Calendar::getByUserId($loggedInUserId);

    $month = date('n');
    $year = date('Y');

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_CALENDAR_NAME . ' / My Calendar';

    require_once __DIR__ . '/../Resources/views/List.php';