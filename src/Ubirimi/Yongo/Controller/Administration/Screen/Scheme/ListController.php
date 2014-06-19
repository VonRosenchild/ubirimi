<?php
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Screen\ScreenScheme;

    Util::checkUserIsLoggedInAndRedirect();

    $screensSchemes = ScreenScheme::getMetaDataByClientId($clientId);
    $menuSelectedCategory = 'issue';

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Screen Schemes';

    require_once __DIR__ . '/../../../../Resources/views/administration/screen/scheme/List.php';