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
    $duplicateName = false;

    if (isset($_POST['copy_permission_scheme'])) {
        $name = Util::cleanRegularInputField($_POST['name']);
        $description = Util::cleanRegularInputField($_POST['description']);

        if (empty($name))
            $emptyName = true;

        $duplicatePermissionScheme = PermissionScheme::getMetaDataByNameAndClientId($clientId, mb_strtolower($name));
        if ($duplicatePermissionScheme)
            $duplicateName = true;

        if (!$emptyName && !$duplicateName) {
            $copiedPermissionScheme = new PermissionScheme($clientId, $name, $description);
            $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));
            $copiedPermissionSchemeId = $copiedPermissionScheme->save($currentDate);

            $permissionSchemeData = PermissionScheme::getDataByPermissionSchemeId($permissionSchemeId);
            while ($permissionSchemeData && $data = $permissionSchemeData->fetch_array(MYSQLI_ASSOC)) {
                $copiedPermissionScheme->addDataRaw($copiedPermissionSchemeId, $data['sys_permission_id'], $data['permission_role_id'], $data['group_id'], $data['user_id'],
                                                    $data['current_assignee'], $data['reporter'], $data['project_lead'], $currentDate);
            }

            Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'Copy Yongo Permission Scheme ' . $permissionScheme['name'], $currentDate);

            header('Location: /yongo/administration/permission-schemes');
        }
    }
    $menuSelectedCategory = 'issue';

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Copy Permission Scheme';

    require_once __DIR__ . '/../../../Resources/views/administration/permission_scheme/Copy.php';