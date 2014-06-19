<?php
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Field\CustomField;

    Util::checkUserIsLoggedInAndRedirect();
    $types = CustomField::getTypes();
    $menuSelectedCategory = 'issue';

    $emptyType = false;
    if (isset($_POST['new_custom_field'])) {
        $type = isset($_POST['type']) ? $_POST['type'] : null;
        if (!$type)
            $emptyType = true;
        else {
            header('Location: /yongo/administration/custom-field/add-data/' . $type);
        }
    }

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Custom Field';

    require_once __DIR__ . '/../../../Resources/views/administration/field/Add.php';