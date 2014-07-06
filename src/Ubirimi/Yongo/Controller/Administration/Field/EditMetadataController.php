<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Field\CustomField;

    Util::checkUserIsLoggedInAndRedirect();

    $Id = $_GET['id'];
    $customField = CustomField::getById($Id);

    if ($customField['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    $emptyName = false;
    $empty_label = false;
    $duplicate_name = false;
    $duplicate_label = false;

    if (isset($_POST['edit_custom_field'])) {
        $name = Util::cleanRegularInputField($_POST['name']);

        $description = Util::cleanRegularInputField($_POST['description']);

        if (empty($name))
            $emptyName = true;

        if (!$emptyName) {
            $date = Util::getServerCurrentDateTime();

            CustomField::updateMetaDataById($Id, $name, $description, $date);
            Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'UPDATE Yongo Custom Field ' . $name, $date);

            header('Location: /yongo/administration/custom-fields');
        }
    }
    $menuSelectedCategory = 'issue';

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Custom Field';

    require_once __DIR__ . '/../../../Resources/views/administration/field/EditMetadata.php';