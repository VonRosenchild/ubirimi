<?php
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Screen\Screen;
    use Ubirimi\Yongo\Repository\Screen\ScreenScheme;

    Util::checkUserIsLoggedInAndRedirect();
    $screenSchemeDataId = $_GET['id'];

    $screens = Screen::getAll($clientId);
    $screenSchemeData = ScreenScheme::getDataByScreenDataId($screenSchemeDataId);
    $screenSchemeId = $screenSchemeData['screen_scheme_id'];
    $operationId = $screenSchemeData['sys_operation_id'];
    $selectedScreenId = $screenSchemeData['screen_id'];
    $screenSchemeMetaData = ScreenScheme::getMetaDataById($screenSchemeData['screen_scheme_id']);

    if ($screenSchemeMetaData['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    if (isset($_POST['edit_screen_scheme'])) {
        $screenId = Util::cleanRegularInputField($_POST['screen']);
        $operationId = Util::cleanRegularInputField($_POST['operation']);

        ScreenScheme::updateDataById($screenSchemeId, $operationId, $screenId);
        header('Location: /yongo/administration/screen/configure-scheme/' . $screenSchemeId);
    }
    $menuSelectedCategory = 'issue';
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Screen Scheme';

    require_once __DIR__ . '/../../../../Resources/views/administration/screen/scheme/EditData.php';