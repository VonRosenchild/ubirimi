<?php
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Screen\Screen;

    Util::checkUserIsLoggedInAndRedirect();
    $screens = Screen::getAll($clientId);

    $menuSelectedCategory = 'issue';

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Screens';
    require_once __DIR__ . '/../../../Resources/views/administration/screen/List.php';