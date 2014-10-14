<?php

namespace Ubirimi\Yongo\Controller\Administration\Role;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Permission\Role;
use Ubirimi\SystemProduct;
use Ubirimi\Repository\Group\Group;

class AssignGroupsConfirmController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $permissionRoleId = $request->get('role_id');
        $role = $this->getRepository('yongo.permission.role')->getPermissionRoleById($permissionRoleId);

        $allGroups = $this->getRepository('ubirimi.user.group')->getByClientIdAndProductId($session->get('client/id'), SystemProduct::SYS_PRODUCT_YONGO);
        $roleGroups = $this->getRepository('yongo.permission.role')->getDefaultGroups($permissionRoleId);

        $role_groups_arr_ids = array();
        while ($roleGroups && $group = $roleGroups->fetch_array(MYSQLI_ASSOC))
            $role_groups_arr_ids[] = $group['group_id'];

        if ($roleGroups)
            $roleGroups->data_seek(0);

        return $this->render(__DIR__ . '/../../../Resources/views/administration/role/AssignGroupsConfirm.php', get_defined_vars());
    }
}
