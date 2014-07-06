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

    if (isset($_POST['edit_field_configuration'])) {
        $name = Util::cleanRegularInputField($_POST['name']);
        $description = Util::cleanRegularInputField($_POST['description']);

        if (empty($name))
            $emptyName = true;

        if (!$emptyName) {
            $currentDate = Util::getServerCurrentDateTime();
            FieldConfiguration::updateMetadataById($fieldConfigurationId, $name, $description, $currentDate);

            Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'UPDATE Yongo Field Configuration ' . $name, $currentDate);

            header('Location: /yongo/administration/field-configurations');
        }
    }
    $menuSelectedCategory = 'issue';

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Field Configuration';

    require_once __DIR__ . '/../../../../Resources/views/administration/field/configuration/EditMetadata.php';