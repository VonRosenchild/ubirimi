<?php
    use Ubirimi\Repository\Client;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $menuSelectedCategory = 'system';
    $settings = Client::getYongoSettings($clientId);

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Attachment Configuration';

    require_once __DIR__ . '/../../../Resources/views/administration/attachment/view_configuration.php';