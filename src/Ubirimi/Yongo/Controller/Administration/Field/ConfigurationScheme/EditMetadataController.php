<?php
    use Ubirimi\Repository\Log;
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
    $emptyName = false;

    if (isset($_POST['edit_field_configuration_scheme'])) {
        $name = Util::cleanRegularInputField($_POST['name']);
        $description = Util::cleanRegularInputField($_POST['description']);

        if (empty($name))
            $emptyName = true;

        if (!$emptyName) {
            $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));
            FieldConfigurationScheme::updateMetaDataById($fieldConfigurationSchemeId, $name, $description, $currentDate);

            Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'UPDATE Yongo Field Configuration Scheme ' . $name, $currentDate);

            header('Location: /yongo/administration/field-configurations/schemes');
        }
    }
    $menuSelectedCategory = 'issue';
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Field Configuration Scheme';

    require_once __DIR__ . '/../../../../Resources/views/administration/field/configuration_scheme/EditMetadata.php';