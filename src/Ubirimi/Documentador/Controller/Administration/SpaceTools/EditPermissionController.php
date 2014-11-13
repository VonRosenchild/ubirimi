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

namespace Ubirimi\Documentador\Controller\Administration\SpaceTools;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Documentador\Repository\Space\Space;
use Ubirimi\Repository\User\UbirimiGroup;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class EditPermissionController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientId = $session->get('client/id');

        $menuSelectedCategory = 'doc_spaces';

        $spaceId = $request->get('id');
        $space = $this->getRepository(Space::class)->getById($spaceId);

        if ($space['client_id'] != $clientId) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        if ($request->request->has('update_configuration')) {

            // deal with the groups
            // delete all the permissions for all groups
            $this->getRepository(Space::class)->removePermissionsForAllGroups($spaceId);
            $groupPermissions = array();

            $requestParameters = $request->request->all();
            foreach ($requestParameters as $key => $value) {

                if (substr($key, 0, 26) == 'space_group_all_view_flag_') {
                    $groupId = str_replace('space_group_all_view_flag_', '', $key);
                    $groupPermissions[$groupId]['all_view_flag'] = 1;
                }
                if (substr($key, 0, 29) == 'space_group_space_admin_flag_') {
                    $groupId = str_replace('space_group_space_admin_flag_', '', $key);
                    $groupPermissions[$groupId]['space_admin_flag'] = 1;
                }
            }

            $this->getRepository(Space::class)->updateGroupPermissions($spaceId, $groupPermissions);

            // deal with the users
            // delete all the permissions for all users
            $this->getRepository(Space::class)->removePermissionsForAllUsers($spaceId);
            $usersPermissions = array();
            foreach ($requestParameters as $key => $value) {

                if (substr($key, 0, 25) == 'space_user_all_view_flag_') {
                    $userId = str_replace('space_user_all_view_flag_', '', $key);
                    $usersPermissions[$userId]['all_view_flag'] = 1;
                }
                if (substr($key, 0, 28) == 'space_user_space_admin_flag_') {
                    $userId = str_replace('space_user_space_admin_flag_', '', $key);
                    $usersPermissions[$userId]['space_admin_flag'] = 1;
                }
            }

            $this->getRepository(Space::class)->updateUserPermissions($spaceId, $usersPermissions);

            // deal with anonymous access
            $anonymous_all_view_flag = $request->request->get('anonymous_all_view_flag');

            $parameters = array(array('field' => 'all_view_flag', 'value' => $anonymous_all_view_flag, 'type' => 'i'));

            $this->getRepository(Space::class)->updatePermissionsAnonymous($spaceId, $parameters);

            return new RedirectResponse('/documentador/administration/space-tools/permissions/' . $spaceId);
        }

        $users = $this->getRepository(UbirimiUser::class)->getByClientId($clientId);
        $groups = $this->getRepository(UbirimiGroup::class)->getByClientIdAndProductId($clientId, SystemProduct::SYS_PRODUCT_DOCUMENTADOR);

        $anonymousAccessSettings = $this->getRepository(Space::class)->getAnonymousAccessSettings($spaceId);

        return $this->render(__DIR__ . '/../../../Resources/views/administration/spacetools/EditPermission.php', get_defined_vars());
    }
}