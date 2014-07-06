<?php
    use Ubirimi\Repository\Log;
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

    $emptyName = false;
    $duplicateName = false;
    if (isset($_POST['copy_screen_scheme'])) {
        $name = Util::cleanRegularInputField($_POST['name']);
        $description = Util::cleanRegularInputField($_POST['description']);

        if (empty($name))
            $emptyName = true;

        $duplicateScreen = ScreenScheme::getMetaDataByNameAndClientId($clientId, mb_strtolower($name));
        if ($duplicateScreen)
            $duplicateName = true;

        if (!$emptyName && !$duplicateName) {
            $copiedScreenScheme = new ScreenScheme($clientId, $name, $description);
            $currentDate = Util::getServerCurrentDateTime();
            $copiedScreenSchemeId = $copiedScreenScheme->save($currentDate);

            $screenSchemeData = ScreenScheme::getDataByScreenSchemeId($screenSchemeId);
            while ($data = $screenSchemeData->fetch_array(MYSQLI_ASSOC)) {
                $copiedScreenScheme->addData($copiedScreenSchemeId, $data['sys_operation_id'], $data['screen_id'], $currentDate);
            }

            Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'Copy Yongo Screen Scheme ' . $screenScheme['name'], $currentDate);

            header('Location: /yongo/administration/screens/schemes');
        }
    }
    $menuSelectedCategory = 'issue';

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Copy Screen Scheme';
    require_once __DIR__ . '/../../../../Resources/views/administration/screen/scheme/Copy.php';