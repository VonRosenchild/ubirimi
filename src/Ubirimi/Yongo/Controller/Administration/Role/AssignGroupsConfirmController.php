<?php

/*
 *  Copyright (C) 2012-2014 SC Ubirimi SRL <info-copyright@ubirimi.com>
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License version 2 as
 *  published by the Free Software Foundation.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301, USA.
 */

namespace Ubirimi\Yongo\Controller\Administration\Role;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\User\UbirimiGroup;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Permission\Role;


class AssignGroupsConfirmController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $permissionRoleId = $request->get('role_id');
        $role = $this->getRepository(Role::class)->getPermissionRoleById($permissionRoleId);

        $allGroups = $this->getRepository(UbirimiGroup::class)->getByClientIdAndProductId($session->get('client/id'), SystemProduct::SYS_PRODUCT_YONGO);
        $roleGroups = $this->getRepository(Role::class)->getDefaultGroups($permissionRoleId);

        $role_groups_arr_ids = array();
        while ($roleGroups && $group = $roleGroups->fetch_array(MYSQLI_ASSOC))
            $role_groups_arr_ids[] = $group['group_id'];

        if ($roleGroups)
            $roleGroups->data_seek(0);

        return $this->render(__DIR__ . '/../../../Resources/views/administration/role/AssignGroupsConfirm.php', get_defined_vars());
    }
}
