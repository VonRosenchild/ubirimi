<?php
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Field\Field;
    use Ubirimi\Yongo\Repository\Field\FieldConfiguration;
    use Ubirimi\Yongo\Repository\Screen\Screen;

    Util::checkUserIsLoggedInAndRedirect();

    $fieldConfigurationId = $_GET['field_configuration_id'];
    $fieldId = $_GET['id'];

    $fieldConfiguration = FieldConfiguration::getMetaDataById($fieldConfigurationId);

    $field = Field::getById($fieldId);
    $screens = Screen::getAll($clientId);

    if (isset($_POST['edit_field_configuration_screen'])) {
        $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));
        Screen::deleteDataByFieldId($fieldId);
        foreach ($_POST as $key => $value) {
            if (substr($key, 0, 13) == 'field_screen_') {
                $data = str_replace('field_screen_', '', $key);
                $values = explode('_', $data);
                $fieldSelectedId = $values[0];
                $screenSelectedId = $values[1];
                Screen::addData($screenSelectedId, $fieldSelectedId, null, $currentDate);
            }
        }

        header('Location: /yongo/administration/field-configuration/edit/' . $fieldConfigurationId);
    }

    $menuSelectedCategory = 'issue';
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Field Configuration Screen';

    require_once __DIR__ . '/../../../../Resources/views/administration/field/configuration/EditScreen.php';