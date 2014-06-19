<?php

    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Field\CustomField;

    Util::checkUserIsLoggedInAndRedirect();
    $fields = CustomField::getAllByClient($clientId);

    $menuSelectedCategory = 'issue';

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Custom Fields';

    require_once __DIR__ . '/../../../Resources/views/administration/field/List.php';