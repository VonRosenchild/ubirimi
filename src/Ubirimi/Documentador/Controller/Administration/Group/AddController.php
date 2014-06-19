<?php
    use Ubirimi\Repository\Group\Group;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();
    $emptyName = false;
    $duplicateName = false;

    if (isset($_POST['new_group'])) {
        $name = Util::cleanRegularInputField($_POST['name']);
        if (empty($name))
            $emptyName = true;

        if (!$emptyName) {
            $groupAlreadyExists = Group::getByNameAndProductId($clientId, SystemProduct::SYS_PRODUCT_DOCUMENTADOR, $name);
            if ($groupAlreadyExists)
                $duplicateName = true;
        }

        if (!$emptyName && !$duplicateName) {
            $description = Util::cleanRegularInputField($_POST['description']);
            $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));
            Group::add($clientId, SystemProduct::SYS_PRODUCT_DOCUMENTADOR, $name, $description, $currentDate);

            header('Location: /documentador/administration/groups');
        }
    }

    $menuSelectedCategory = 'doc_users';

    require_once __DIR__ . '/../../../Resources/views/administration/group/Add.php';