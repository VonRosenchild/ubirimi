<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Permission\PermissionRole;

    Util::checkUserIsLoggedInAndRedirect();

    $emptyName = false;
    $alreadyExists = false;

    if (isset($_POST['new_role'])) {
        $name = Util::cleanRegularInputField($_POST['name']);
        $description = Util::cleanRegularInputField($_POST['description']);

        if (empty($name))
            $emptyName = true;

        $role = PermissionRole::getByName($clientId, $name);
        if ($role)
            $alreadyExists = true;

        if (!$emptyName && !$alreadyExists) {
            $date = Util::getCurrentDateTime($session->get('client/settings/timezone'));
            PermissionRole::add($clientId, $name, $description, $date);

            Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'ADD Yongo Project Role ' . $name, $date);

            header('Location: /yongo/administration/roles');
        }
    }

    $menuSelectedCategory = 'user';
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Role';

    require_once __DIR__ . '/../../../Resources/views/administration/role/Add.php';