<?php

namespace Ubirimi\Yongo\Controller\Administration\Role;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Permission\Role;

class DeleteConfirmController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $permissionRoleId = $request->get('perm_role_id');
        $role = $this->getRepository(Role::class)->getPermissionRoleById($permissionRoleId);

        return $this->render(__DIR__ . '/../../../Resources/views/administration/role/DeleteConfirm.php', get_defined_vars());
    }
}
