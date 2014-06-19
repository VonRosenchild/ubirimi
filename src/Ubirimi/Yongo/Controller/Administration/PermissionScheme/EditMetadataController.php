<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Permission\PermissionScheme;

    Util::checkUserIsLoggedInAndRedirect();

    $permissionSchemeId = $_GET['id'];
    $permissionScheme = PermissionScheme::getMetaDataById($permissionSchemeId);

    if ($permissionScheme['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    $emptyName = false;
    if (isset($_POST['edit_permission_scheme'])) {
        $name = Util::cleanRegularInputField($_POST['name']);
        $description = Util::cleanRegularInputField($_POST['description']);

        if (empty($name))
            $emptyName = true;

        if (!$emptyName) {
            $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));
            PermissionScheme::updateMetaDataById($permissionSchemeId, $name, $description, $currentDate);

            Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'UPDATE Yongo Permission Scheme ' . $name, $currentDate);

            header('Location: /yongo/administration/permission-schemes');
        }
    }

    $menuSelectedCategory = 'issue';
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Issue Permission Scheme';

    require_once __DIR__ . '/../../../Resources/views/administration/permission_scheme/EditMetadata.php';