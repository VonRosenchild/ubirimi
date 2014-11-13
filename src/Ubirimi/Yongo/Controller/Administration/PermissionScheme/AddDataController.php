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

namespace Ubirimi\Yongo\Controller\Administration\PermissionScheme;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\Repository\User\UbirimiGroup;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Permission\Permission;
use Ubirimi\Yongo\Repository\Permission\PermissionScheme;
use Ubirimi\Yongo\Repository\Permission\Role;


class AddDataController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $permissionSchemeId = $request->get('perm_scheme_id');
        $permissionId = $request->get('id');

        $permissionScheme = $this->getRepository(PermissionScheme::class)->getMetaDataById($permissionSchemeId);
        $permissions = $this->getRepository(Permission::class)->getAll();

        $users = $this->getRepository(UbirimiUser::class)->getByClientId($session->get('client/id'));
        $groups = $this->getRepository(UbirimiGroup::class)->getByClientIdAndProductId($session->get('client/id'), SystemProduct::SYS_PRODUCT_YONGO);
        $roles = $this->getRepository(Role::class)->getByClient($session->get('client/id'));

        if ($request->request->has('confirm_new_data')) {

            $sysPermissionIds = $request->request->get('permission');

            $permissionType = $request->request->get('type');

            $user = $request->request->get('user');
            $group = $request->request->get('group');
            $role = $request->request->get('role');
            $currentDate = Util::getServerCurrentDateTime();

            if ($permissionType) {
                for ($i = 0; $i < count($sysPermissionIds); $i++){
                    // check for duplicate information
                    $duplication = false;
                    $dataPermission = $this->getRepository(PermissionScheme::class)->getDataByPermissionSchemeIdAndPermissionId(
                        $permissionSchemeId,
                        $sysPermissionIds[$i]
                    );

                    if ($dataPermission) {
                        while ($data = $dataPermission->fetch_array(MYSQLI_ASSOC)) {

                            if (isset($data['group_id']) && $group && $data['group_id'] == $group)
                                $duplication = true;
                            if ($data['user_id'] && $data['user_id'] == $user)
                                $duplication = true;
                            if ($data['permission_role_id'] && $data['permission_role_id'] == $role) {
                                $duplication = true;
                            }

                            if ($permissionType == Permission::PERMISSION_TYPE_PROJECT_LEAD)
                                if ($data['project_lead'])
                                    $duplication = true;
                            if ($permissionType == Permission::PERMISSION_TYPE_CURRENT_ASSIGNEE)
                                if ($data['current_assignee'])
                                    $duplication = true;
                            if ($permissionType == Permission::PERMISSION_TYPE_REPORTER)
                                if ($data['reporter'])
                                    $duplication = true;
                        }
                    }

                    if (!$duplication) {
                        $this->getRepository(PermissionScheme::class)->addData(
                            $permissionSchemeId,
                            $sysPermissionIds[$i],
                            $permissionType,
                            $role,
                            $group,
                            $user,
                            $currentDate
                        );

                        $this->getRepository(UbirimiLog::class)->add(
                            $session->get('client/id'),
                            SystemProduct::SYS_PRODUCT_YONGO,
                            $session->get('user/id'),
                            'ADD Yongo Permission Scheme Data',
                            $currentDate
                        );
                    }
                }

            }

            return new RedirectResponse('/yongo/administration/permission-scheme/edit/' . $permissionSchemeId);
        }

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Permission Data';

        return $this->render(__DIR__ . '/../../../Resources/views/administration/permission_scheme/AddData.php', get_defined_vars());
    }
}
