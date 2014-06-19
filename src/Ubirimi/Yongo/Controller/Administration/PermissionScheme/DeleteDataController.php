<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Permission\PermissionScheme;

    Util::checkUserIsLoggedInAndRedirect();

    $permissionSchemeDataId = $_POST['permission_scheme_data_id'];
    PermissionScheme::deleteDataById($permissionSchemeDataId);

    $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));
    Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'DELETE Yongo Permission Scheme Data', $currentDate);