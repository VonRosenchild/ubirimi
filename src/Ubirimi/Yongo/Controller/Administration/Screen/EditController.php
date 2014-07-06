<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Screen\Screen;

    Util::checkUserIsLoggedInAndRedirect();

    $screenId = $_GET['id'];

    $screen = Screen::getMetaDataById($screenId);
    if ($screen['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    $emptyScreenName = false;
    $screenExists = false;

    if (isset($_POST['edit_workflow_screen'])) {
        $name = Util::cleanRegularInputField($_POST['name']);
        $description = Util::cleanRegularInputField($_POST['description']);

        if (empty($name))
            $emptyScreenName = true;

        // check for duplication
        $screen_row_exists = Screen::getByNameAndId($clientId, mb_strtolower($name), $screenId);

        if ($screen_row_exists)
            $screenExists = true;

        if (!$screenExists && !$emptyScreenName) {
            $currentDate = Util::getServerCurrentDateTime();
            Screen::updateMetadataById($screenId, $name, $description, $currentDate);

            Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'UPDATE Yongo Screen ' . $name, $currentDate);

            header('Location: /yongo/administration/screens');
        }
    }

    $menuSelectedCategory = 'issue';
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Screen';
    require_once __DIR__ . '/../../../Resources/views/administration/screen/Edit.php';