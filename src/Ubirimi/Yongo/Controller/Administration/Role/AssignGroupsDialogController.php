<?php

namespace Ubirimi\Yongo\Controller\Administration\Role;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Project\Project;

use Ubirimi\SystemProduct;
use Ubirimi\Yongo\Repository\Permission\Role;

class AssignGroupsDialogController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $permissionRoleId = $request->get('role_id');
        $projectId = $request->get('project_id');
        $role = $this->getRepository('yongo.permission.role')->getPermissionRoleById($permissionRoleId);

        $all_groups = $this->getRepository('ubirimi.user.group')->getByClientIdAndProductId($session->get('client/id'), SystemProduct::SYS_PRODUCT_YONGO);
        $role_groups = $this->getRepository('yongo.project.project')->getGroupsInRole($projectId, $permissionRoleId);

        $role_groups_arr_ids = array();
        while ($role_groups && $group = $role_groups->fetch_array(MYSQLI_ASSOC))
            $role_groups_arr_ids[] = $group['group_id'];

        if ($role_groups) {
            $role_groups->data_seek(0);
        }

        return $this->render(__DIR__ . '/../../../Resources/views/administration/role/AssignGroupsDialog.php', get_defined_vars());
    }
}
