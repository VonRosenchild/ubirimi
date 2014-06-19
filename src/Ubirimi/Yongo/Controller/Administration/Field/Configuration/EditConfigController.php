<?php
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Field\Field;
    use Ubirimi\Yongo\Repository\Field\FieldConfiguration;

    Util::checkUserIsLoggedInAndRedirect();
    $fieldConfigurationId = $_GET['field_configuration_id'];
    $fieldId = $_GET['id'];

    $fieldConfiguration = FieldConfiguration::getMetaDataById($fieldConfigurationId);

    if ($fieldConfiguration['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    $fieldConfigurationData = FieldConfiguration::getDataByConfigurationAndField($fieldConfigurationId, $fieldId);
    $description = $fieldConfigurationData['field_description'];
    $field = Field::getById($fieldId);
    if ($field['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    $menuSelectedCategory = 'issue';

    if (isset($_POST['edit_field_configuration'])) {
        $description = $_POST['description'];
        FieldConfiguration::updateFieldDescription($fieldConfigurationId, $fieldId, $description);

        header('Location: /yongo/administration/field-configuration/edit/' . $fieldConfigurationId);
    }
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Field';

    require_once __DIR__ . '/../../../../Resources/views/administration/field/configuration/EditConfig.php';