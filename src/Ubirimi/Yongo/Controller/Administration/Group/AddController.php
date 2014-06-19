<?php
    use Ubirimi\Repository\Group\Group;
    use Ubirimi\Repository\Log;
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
            $groupAlreadyExists = Group::getByNameAndProductId($clientId, SystemProduct::SYS_PRODUCT_YONGO, $name);
            if ($groupAlreadyExists)
                $duplicateName = true;
        }

        if (!$emptyName && !$duplicateName) {
            $description = Util::cleanRegularInputField($_POST['description']);
            $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));
            Group::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $name, $description, $currentDate);

            Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'ADD Yongo Group ' . $name, $currentDate);

            header('Location: /yongo/administration/groups');
        }
    }

    $menuSelectedCategory = 'user';

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Group';

    require_once __DIR__ . '/../../../Resources/views/administration/group/Add.php';