<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Field\Field;
    use Ubirimi\Yongo\Repository\Screen\Screen;

    Util::checkUserIsLoggedInAndRedirect();

    $screenId = $_GET['id'];

    $screenMetadata = Screen::getMetaDataById($screenId);
    if ($screenMetadata['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    $position = isset($_GET['position']) ? $_GET['position'] : null;
    $fieldId = isset($_GET['field_id']) ? $_GET['field_id'] : null;

    if ($fieldId && $position) {
        Screen::updatePositionForField($screenId, $fieldId, $position);
        header('Location: /yongo/administration/screen/configure/' . $screenId);
    }

    $fields = Field::getByClient($clientId);

    if (isset($_POST['add_screen_field'])) {
        $fieldId = Util::cleanRegularInputField($_POST['field']);

        if ($fieldId != -1) {
            $currentDate = Util::getServerCurrentDateTime();
            $lastOrder = Screen::getLastOrderNumber($screenId);
            Screen::addData($screenId, $fieldId, ($lastOrder + 1), $currentDate);

            Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'UPDATE Yongo Screen Data ' . $screenMetadata['name'], $currentDate);

            header('Location: /yongo/administration/screen/configure/' . $screenId);
        }
    }

    $screenData = Screen::getDataById($screenId);
    $menuSelectedCategory = 'issue';

    $source = isset($_GET['source']) ? $_GET['source'] : null;
    $projectId = null;
    if ($source == 'project_screen' || $source == 'project_field') {
        $projectId = $_GET['project_id'];
    }
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Screen';

    require_once __DIR__ . '/../../../Resources/views/administration/screen/Configure.php';