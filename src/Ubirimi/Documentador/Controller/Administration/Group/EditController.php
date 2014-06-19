<?php
    use Ubirimi\Repository\Group\Group;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();
    $Id = $_GET['id'];
    $group = Group::getMetadataById($Id);

    if ($group['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    $name = $group['name'];
    $description = $group['description'];

    $emptyName = false;
    $duplicateName = false;

    if (isset($_POST['update_group'])) {
        $name = Util::cleanRegularInputField($_POST['name']);
        $description = Util::cleanRegularInputField($_POST['description']);

        if (empty($name))
            $emptyName = true;

        if (!$emptyName) {
            $groupAlreadyExists = Group::getByNameAndProductId($clientId, SystemProduct::SYS_PRODUCT_YONGO, mb_strtolower($name), $Id);

            if ($groupAlreadyExists)
                $duplicateName = true;
        }

        if (!$emptyName && !$duplicateName) {
            $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));
            Group::updateById($Id, $name, $description, $currentDate);

            header('Location: /documentador/administration/groups');
        }
    }
    $menuSelectedCategory = 'doc_users';

    require_once __DIR__ . '/../../../Resources/views/administration/group/Edit.php';