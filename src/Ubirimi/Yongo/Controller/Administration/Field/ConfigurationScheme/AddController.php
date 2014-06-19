<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Field\FieldConfigurationScheme;
    use Ubirimi\Yongo\Repository\Issue\IssueType;

    Util::checkUserIsLoggedInAndRedirect();

    $emptyName = false;

    if (isset($_POST['add_field_configuration_scheme'])) {
        $name = Util::cleanRegularInputField($_POST['name']);
        $description = Util::cleanRegularInputField($_POST['description']);

        if (empty($name))
            $emptyName = true;

        if (!$emptyName) {
            $fieldConfigurationScheme = new FieldConfigurationScheme($clientId, $name, $description);
            $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));
            $fieldConfigurationSchemeId = $fieldConfigurationScheme->save($currentDate);

            $issueTypes = IssueType::getAll($clientId);
            while ($issueType = $issueTypes->fetch_array(MYSQLI_ASSOC)) {
                FieldConfigurationScheme::addData($fieldConfigurationSchemeId, null, $issueType['id'], $currentDate);
            }

            Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'ADD Yongo Field Configuration Scheme ' . $name, $currentDate);

            header('Location: /yongo/administration/field-configurations/schemes');
        }
    }
    $menuSelectedCategory = 'issue';

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Event';

    require_once __DIR__ . '/../../../../Resources/views/administration/field/configuration_scheme/Add.php';