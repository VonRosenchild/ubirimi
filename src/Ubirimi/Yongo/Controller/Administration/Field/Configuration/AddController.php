<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Field\FieldConfiguration;

    Util::checkUserIsLoggedInAndRedirect();

    $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_YONGO);
    $emptyName = false;

    if (isset($_POST['add_field_configuration'])) {
        $name = Util::cleanRegularInputField($_POST['name']);
        $description = Util::cleanRegularInputField($_POST['description']);

        if (empty($name))
            $emptyName = true;

        if (!$emptyName) {
            $fieldConfiguration = new FieldConfiguration($clientId, $name, $description);
            $currentDate = Util::getServerCurrentDateTime();
            $fieldConfiguration->save($currentDate);

            Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'ADD Yongo Field Configuration ' . $name, $currentDate);

            header('Location: /yongo/administration/field-configurations');
        }
    }

    $menuSelectedCategory = 'issue';

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Add Field Configuration Scheme';

    require_once __DIR__ . '/../../../../Resources/views/administration/field/configuration/Add.php';