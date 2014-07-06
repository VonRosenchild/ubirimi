<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Permission\PermissionRole;

    Util::checkUserIsLoggedInAndRedirect();

    $permissionRoleId = $_GET['id'];
    $perm_role = PermissionRole::getById($permissionRoleId);

    if ($perm_role['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    $emptyName = false;
    $alreadyExists = false;

    if (isset($_POST['confirm_edit_perm_role'])) {
        $name = Util::cleanRegularInputField($_POST['name']);
        $description = Util::cleanRegularInputField($_POST['description']);

        if (empty($name))
            $emptyName = true;

        $role = PermissionRole::getByName($clientId, $name, $permissionRoleId);
        if ($role)
            $alreadyExists = true;

        if (!$emptyName && !$alreadyExists) {
            $date = Util::getServerCurrentDateTime();
            PermissionRole::updateById($permissionRoleId, $name, $description, $date);

            Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'UPDATE Yongo Project Role ' . $name, $date);
            header('Location: /yongo/administration/roles');
        }
    }
    $menuSelectedCategory = 'user';
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Project Role';

    require_once __DIR__ . '/../../../Resources/views/administration/role/Edit.php';