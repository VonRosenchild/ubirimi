<?php
    use Ubirimi\Repository\Group\Group;
    use Ubirimi\Repository\Log;
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

            Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'UPDATE Yongo Group ' . $name, $currentDate);

            header('Location: /yongo/administration/groups');
        }
    }

    $menuSelectedCategory = 'user';

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Group';

    require_once __DIR__ . '/../../../Resources/views/administration/group/Edit.php';