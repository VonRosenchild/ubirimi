<?php
    use Ubirimi\Repository\Client;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();
    $settings = Client::getYongoSettings($clientId);

    $menuSelectedCategory = 'user';

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / User Preferences';

    require_once __DIR__ . '/../../../Resources/views/administration/user/ViewPreference.php';