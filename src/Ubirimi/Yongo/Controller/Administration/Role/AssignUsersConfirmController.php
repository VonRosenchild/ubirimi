<?php

namespace Ubirimi\Yongo\Controller\Administration\Role;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Permission\Role;
use Ubirimi\Repository\Client;

class AssignUsersConfirmController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $permissionRoleId = $request->get('role_id');
        $role = Role::getPermissionRoleById($permissionRoleId);

        $allUsers = Client::getUsers($session->get('client/id'));
        $roleUsers = Role::getDefaultUsers($permissionRoleId);

        $role_users_arr_ids = array();
        while ($roleUsers && $user = $roleUsers->fetch_array(MYSQLI_ASSOC))
            $role_users_arr_ids[] = $user['user_id'];

        if ($roleUsers)
            $roleUsers->data_seek(0);

        return $this->render(__DIR__ . '/../../../Resources/views/administration/role/AssignUsersConfirm.php', get_defined_vars());
    }
}
