<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Field\Field;
    use Ubirimi\Yongo\Repository\Field\FieldConfiguration;
    use Ubirimi\Yongo\Repository\Screen\Screen;

    Util::checkUserIsLoggedInAndRedirect();

    $fieldId = $_GET['id'];
    $field = Field::getById($fieldId);

    if ($field['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    $screens = Screen::getAll($clientId);

    if (isset($_POST['edit_field_custom_screen'])) {
        $currentDate = Util::getServerCurrentDateTime();

        while ($screen = $screens->fetch_array(MYSQLI_ASSOC)) {
            Screen::deleteDataByScreenIdAndFieldId($screen['id'], $fieldId);
        }

        foreach ($_POST as $key => $value) {

            if (substr($key, 0, 13) == 'field_screen_') {
                $data = str_replace('field_screen_', '', $key);
                $values = explode('_', $data);
                $fieldSelectedId = $values[0];
                $screenSelectedId = $values[1];
                Screen::addData($screenSelectedId, $fieldSelectedId, null, $currentDate);
            }
        }

        // make field visible in all the field configurations

        $fieldConfigurations = FieldConfiguration::getByClientId($clientId);
        while ($fieldConfiguration = $fieldConfigurations->fetch_array(MYSQLI_ASSOC)) {
            FieldConfiguration::addCompleteData($fieldConfiguration['id'], $fieldId, 1, 0, '');
        }

        Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'UPDATE Yongo Custom Field ' . $field['name'], $currentDate);

        header('Location: /yongo/administration/custom-fields');
    }

    $menuSelectedCategory = 'issue';
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Copy Custome Field';

    require_once __DIR__ . '/../../../Resources/views/administration/field/EditScreen.php';