<?php

namespace Ubirimi\Yongo\Controller\Administration\Role;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Permission\Role;
use Ubirimi\Yongo\Repository\Project\YongoProject;

class AssignUsersDialogController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $permissionRoleId = $request->get('role_id');
        $projectId = $request->get('project_id');
        $role = $this->getRepository(Role::class)->getPermissionRoleById($permissionRoleId);

        $allUsers = $this->getRepository(UbirimiClient::class)->getUsers($session->get('client/id'));
        $roleUsers = $this->getRepository(YongoProject::class)->getUsersInRole($projectId, $permissionRoleId);

        $role_users_arr_ids = array();
        while ($roleUsers && $user = $roleUsers->fetch_array(MYSQLI_ASSOC))
            $role_users_arr_ids[] = $user['user_id'];

        if ($roleUsers)
            $roleUsers->data_seek(0);

        return $this->render(__DIR__ . '/../../../Resources/views/administration/role/AssignUsersDialog.php', get_defined_vars());
    }
}
