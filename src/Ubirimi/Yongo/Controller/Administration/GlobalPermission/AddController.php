<?php
    use Ubirimi\Repository\Group\Group;
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Permission\GlobalPermission;

    Util::checkUserIsLoggedInAndRedirect();

    $allGroups = Group::getByClientIdAndProductId($clientId, SystemProduct::SYS_PRODUCT_YONGO);
    $globalPermissions = GlobalPermission::getAllByProductId(SystemProduct::SYS_PRODUCT_YONGO);

    if (isset($_POST['confirm_new_permission'])) {
        $permissionId = $_POST['permission'];
        $groupId = $_POST['group'];
        $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));
        $group = Group::getMetadataById($groupId);
        $permission = GlobalPermission::getById($permissionId);

        $date = Util::getCurrentDateTime($session->get('client/settings/timezone'));

        // check if the group is already added
        $permissionData = GlobalPermission::getDataByPermissionIdAndGroupId($clientId, $permissionId, $groupId);

        if (!$permissionData) {
            GlobalPermission::addDataForGroupId($clientId, $permissionId, $groupId, $date);
            Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'ADD Yongo Global Permission ' . $permission['name'] . ' to group ' . $group['name'], $currentDate);
        }

        header('Location: /yongo/administration/global-permissions');
    }

    $menuSelectedCategory = 'user';

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Global Permission';

    require_once __DIR__ . '/../../../Resources/views/administration/global_permission/Add.php';