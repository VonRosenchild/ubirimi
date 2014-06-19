<?php
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Screen\ScreenScheme;

    Util::checkUserIsLoggedInAndRedirect();

    $screenSchemeId = $_GET['id'];
    $screenScheme = ScreenScheme::getMetaDataById($screenSchemeId);

    if ($screenScheme['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    $screenSchemeData = ScreenScheme::getDataByScreenSchemeId($screenSchemeId);
    $menuSelectedCategory = 'issue';
    $source = isset($_GET['source']) ? $_GET['source'] : null;
    $projectId = null;

    if ($source == 'project') {
        $projectId = $_GET['project_id'];
    }
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Screen Scheme';
    require_once __DIR__ . '/../../../../Resources/views/administration/screen/scheme/Configure.php';