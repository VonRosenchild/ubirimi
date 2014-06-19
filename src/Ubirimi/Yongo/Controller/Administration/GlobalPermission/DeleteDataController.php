<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Permission\GlobalPermission;

    Util::checkUserIsLoggedInAndRedirect();

    $Id = $_GET['id'];
    $permissionData = GlobalPermission::getDataById($Id);
    GlobalPermission::deleteById($Id);

    $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));
    Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'DELETE Yongo Global Permission ' . $permissionData['permission_name'] . ' from group ' . $permissionData['name'], $currentDate);

    header('Location: /yongo/administration/global-permissions');