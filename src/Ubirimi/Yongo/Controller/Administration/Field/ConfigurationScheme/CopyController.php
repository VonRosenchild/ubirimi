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
    $duplicateName = false;

    if (isset($_POST['copy_field_configuration_scheme'])) {
        $name = Util::cleanRegularInputField($_POST['name']);
        $description = Util::cleanRegularInputField($_POST['description']);

        if (empty($name)) {
            $emptyName = true;
        }

        $duplicateFieldConfigurationScheme = FieldConfigurationScheme::getMetaDataByNameAndClientId($clientId, mb_strtolower($name));
        if ($duplicateFieldConfigurationScheme)
            $duplicateName = true;

        if (!$emptyName && !$duplicateName) {
            $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));

            $copiedFieldConfigurationScheme = new FieldConfigurationScheme($clientId, $name, $description);
            $copiedFieldConfigurationSchemeId = $copiedFieldConfigurationScheme->save($currentDate);

            $fieldConfigurationSchemeData = FieldConfigurationScheme::getDataByFieldConfigurationSchemeId($fieldConfigurationSchemeId);

            while ($fieldConfigurationSchemeData && $data = $fieldConfigurationSchemeData->fetch_array(MYSQLI_ASSOC)) {

                $copiedFieldConfigurationScheme->addData($copiedFieldConfigurationSchemeId, $data['field_configuration_id'], $data['issue_type_id'], $currentDate);
            }

            Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'Copy Yongo Field Configuration Scheme ' . $fieldConfigurationScheme['name'], $currentDate);

            header('Location: /yongo/administration/field-configurations/schemes');
        }
    }
    $menuSelectedCategory = 'issue';

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Copy Field Configuration Scheme';

    require_once __DIR__ . '/../../../../Resources/views/administration/field/configuration_scheme/Copy.php';