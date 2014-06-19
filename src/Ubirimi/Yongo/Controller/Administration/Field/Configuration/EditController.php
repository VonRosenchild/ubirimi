<?php
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Field\Field;
    use Ubirimi\Yongo\Repository\Field\FieldConfiguration;

    Util::checkUserIsLoggedInAndRedirect();
    $fieldConfigurationId = $_GET['id'];
    $fieldConfiguration = FieldConfiguration::getMetaDataById($fieldConfigurationId);
    if ($fieldConfiguration['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    $emptyName = false;

    $allFields = Field::getByClient($clientId);
    $menuSelectedCategory = 'issue';

    $source = isset($_GET['source']) ? $_GET['source'] : null;
    $projectId = null;
    if ($source == 'project') {
        $projectId = $_GET['project_id'];
    }

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Field Configuration';

    require_once __DIR__ . '/../../../../Resources/views/administration/field/configuration/Edit.php';