<?php
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Field\FieldConfiguration;

    Util::checkUserIsLoggedInAndRedirect();
    $fieldConfigurations = FieldConfiguration::getByClientId($clientId);

    $menuSelectedCategory = 'issue';

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Field Configurations';

    require_once __DIR__ . '/../../../../Resources/views/administration/field/configuration/List.php';