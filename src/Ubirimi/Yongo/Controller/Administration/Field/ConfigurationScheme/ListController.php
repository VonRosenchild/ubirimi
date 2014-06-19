<?php
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Field\FieldConfigurationScheme;

    Util::checkUserIsLoggedInAndRedirect();
    $fieldConfigurationSchemes = FieldConfigurationScheme::getByClient($clientId);

    $menuSelectedCategory = 'issue';

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Field Configuration Schemes';

    require_once __DIR__ . '/../../../../Resources/views/administration/field/configuration_scheme/List.php';