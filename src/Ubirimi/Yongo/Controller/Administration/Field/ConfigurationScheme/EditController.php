<?php
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Field\FieldConfigurationScheme;

    Util::checkUserIsLoggedInAndRedirect();
    $fieldConfigurationSchemeId = $_GET['id'];
    $fieldConfigurationScheme = FieldConfigurationScheme::getMetaDataById($fieldConfigurationSchemeId);

    if ($fieldConfigurationScheme['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    $fieldConfigurationSchemeData = FieldConfigurationScheme::getDataByFieldConfigurationSchemeId($fieldConfigurationSchemeId);
    $menuSelectedCategory = 'issue';

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Field Configuration Scheme';

    require_once __DIR__ . '/../../../../Resources/views/administration/field/configuration_scheme/Edit.php';