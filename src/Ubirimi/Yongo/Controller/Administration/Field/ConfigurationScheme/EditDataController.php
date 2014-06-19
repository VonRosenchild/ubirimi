<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Field\FieldConfiguration;
    use Ubirimi\Yongo\Repository\Field\FieldConfigurationScheme;

    Util::checkUserIsLoggedInAndRedirect();

    $fieldConfigurationSchemeDataId = $_GET['id'];
    $fieldConfigurations = FieldConfiguration::getByClientId($clientId);
    $fieldConfigurationSchemeData = FieldConfigurationScheme::getDataById($fieldConfigurationSchemeDataId);

    $fieldConfigurationSchemeId = $fieldConfigurationSchemeData['issue_type_field_configuration_id'];
    $fieldConfigurationScheme = FieldConfigurationScheme::getMetaDataById($fieldConfigurationSchemeId);

    if ($fieldConfigurationScheme['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    if (isset($_POST['edit_field_configuration_scheme_data'])) {
        $fieldConfigurationId = Util::cleanRegularInputField($_POST['field_configuration']);
        $issueTypeId = Util::cleanRegularInputField($_POST['issue_type']);

        FieldConfigurationScheme::updateDataById($fieldConfigurationId, $fieldConfigurationSchemeId, $issueTypeId);

        $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));
        Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'UPDATE Yongo Field Configuration Scheme ' . $fieldConfigurationScheme['name'], $currentDate);

        header('Location: /yongo/administration/field-configuration/scheme/edit/' . $fieldConfigurationSchemeId);
    }
    $menuSelectedCategory = 'issue';

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Field Configuration';

    require_once __DIR__ . '/../../../../Resources/views/administration/field/configuration_scheme/EditData.php';