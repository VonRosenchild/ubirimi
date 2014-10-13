<?php
    use Ubirimi\Repository\Client;
    use Ubirimi\Repository\Group\Group;
    use Ubirimi\Repository\User\User;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Permission\GlobalPermission;

    Util::checkUserIsLoggedInAndRedirect();

    $menuSelectedCategory = 'doc_administration';

    $globalsPermissions = GlobalPermission::getAllByProductId(SystemProduct::SYS_PRODUCT_DOCUMENTADOR);

    if (isset($_POST['update_configuration'])) {

        $anonymous_use_flag = isset($_POST['anonymous_use_flag']) ? $_POST['anonymous_use_flag'] : 0;
        $anonymous_view_user_profile_flag = isset($_POST['anonymous_view_user_profile_flag']) ? $_POST['anonymous_view_user_profile_flag'] : 0;

        $parameters = array(array('field' => 'anonymous_use_flag', 'value' => $anonymous_use_flag, 'type' => 'i'),
            array('field' => 'anonymous_view_user_profile_flag', 'value' => $anonymous_view_user_profile_flag, 'type' => 'i'));

        $this->getRepository('ubirimi.general.client')->updateProductSettings($clientId, SystemProduct::SYS_PRODUCT_DOCUMENTADOR, $parameters);

        // deal with permissions added to groups

        // delete first all the permissions related to groups
        while ($globalsPermission = $globalsPermissions->fetch_array(MYSQLI_ASSOC)) {
            GlobalPermission::deleteByPermissionId($clientId, $globalsPermission['id'], 'group');
        }

        $date = Util::getServerCurrentDateTime();

        foreach ($_POST as $key => $value) {
            if (substr($key, 0, 5) == 'group') {
                $data = explode("_", $key);
                $globalsPermissionId = $data[1];
                $groupId = $data[2];

                GlobalPermission::addDataForGroupId($clientId, $globalsPermissionId, $groupId, $date);
            }
        }

        // deal with permissions added to users

        // delete first all the permissions related to individual users
        while ($globalsPermission = $globalsPermissions->fetch_array(MYSQLI_ASSOC)) {
            GlobalPermission::deleteByPermissionId($clientId, $globalsPermission['id'], 'user');
        }

        foreach ($_POST as $key => $value) {
            if (substr($key, 0, 4) == 'user') {
                $data = explode("_", $key);
                $globalsPermissionId = $data[1];
                $userId = $data[2];

                GlobalPermission::addDataForUserId($clientId, $globalsPermissionId, $userId);
            }
        }

        header('Location: /documentador/administration/global-permissions');
    }
    $documentatorSettings = $this->getRepository('ubirimi.general.client')->getDocumentatorSettings($clientId);
    $session->set('documentator/settings', $documentatorSettings);

    $users = $this->getRepository('ubirimi.user.user')->getByClientId($clientId);
    $groups = $this->getRepository('ubirimi.user.group')->getByClientIdAndProductId($clientId, SystemProduct::SYS_PRODUCT_DOCUMENTADOR);

    require_once __DIR__ . '/../../../Resources/views/administration/globalpermissions/Edit.php';