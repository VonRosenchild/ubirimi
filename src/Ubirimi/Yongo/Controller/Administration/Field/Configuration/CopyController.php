<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Field\FieldConfiguration;

    Util::checkUserIsLoggedInAndRedirect();

    $fieldConfigurationId = $_GET['id'];
    $fieldConfiguration = FieldConfiguration::getMetaDataById($fieldConfigurationId);

    if ($fieldConfiguration['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    $emptyName = false;
    $duplicateName = false;

    if (isset($_POST['copy_field_configuration'])) {
        $name = Util::cleanRegularInputField($_POST['name']);
        $description = Util::cleanRegularInputField($_POST['description']);

        if (empty($name)) {
            $emptyName = true;
        }

        $duplicateFieldConfiguration = FieldConfiguration::getMetaDataByNameAndClientId($clientId, mb_strtolower($name));
        if ($duplicateFieldConfiguration)
            $duplicateName = true;

        if (!$emptyName && !$duplicateName) {
            $copiedFieldConfiguration = new FieldConfiguration($clientId, $name, $description);

            $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));
            $copiedFieldConfigurationId = $copiedFieldConfiguration->save($currentDate);

            $fieldConfigurationData = FieldConfiguration::getDataByConfigurationId($fieldConfigurationId);

            while ($fieldConfigurationData && $data = $fieldConfigurationData->fetch_array(MYSQLI_ASSOC)) {

                $copiedFieldConfiguration->addCompleteData($copiedFieldConfigurationId, $data['field_id'], $data['visible_flag'], $data['required_flag'], $data['field_description']);
            }

            Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'Copy Yongo Field Configuration ' . $fieldConfiguration['name'], $currentDate);

            header('Location: /yongo/administration/field-configurations');
        }
    }
    $menuSelectedCategory = 'issue';

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Copy Field Configuration';

    require_once __DIR__ . '/../../../../Resources/views/administration/field/configuration/Copy.php';