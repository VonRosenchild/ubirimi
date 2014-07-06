<?php
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
        $screen_row_exists = Screen::getByName($clientId, mb_strtolower($name));

        if ($screen_row_exists)
            $screenExists = true;

        if (!$screenExists && !$emptyScreenName) {
            $copiedScreen = new Screen($clientId, $name, $description);
            $currentDate = Util::getServerCurrentDateTime();
            $copiedScreenId = $copiedScreen->save($currentDate);

            $screenData = Screen::getDataById($screenId);
            while ($data = $screenData->fetch_array(MYSQLI_ASSOC)) {
                Screen::addData($copiedScreenId, $data['field_id'], $data['position'], $currentDate);
            }

            header('Location: /yongo/administration/screens');
        }
    }

    $menuSelectedCategory = 'issue';
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Copy Screen';
    require_once __DIR__ . '/../../../Resources/views/administration/screen/Copy.php';