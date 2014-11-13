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

namespace Ubirimi\Documentador\Controller\Administration\GlobalPermissions;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\Repository\User\UbirimiGroup;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Permission\GlobalPermission;

class EditController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientId = $session->get('client/id');

        $menuSelectedCategory = 'doc_users';

        $globalsPermissions = $this->getRepository(GlobalPermission::class)->getAllByProductId(SystemProduct::SYS_PRODUCT_DOCUMENTADOR);

        if ($request->request->has('update_configuration')) {

            $anonymous_use_flag = $request->request->get('anonymous_use_flag');
            $anonymous_view_user_profile_flag = $request->request->get('anonymous_view_user_profile_flag');

            $parameters = array(array('field' => 'anonymous_use_flag', 'value' => $anonymous_use_flag, 'type' => 'i'),
                array('field' => 'anonymous_view_user_profile_flag', 'value' => $anonymous_view_user_profile_flag, 'type' => 'i'));

            $this->getRepository(UbirimiClient::class)->updateProductSettings($clientId, SystemProduct::SYS_PRODUCT_DOCUMENTADOR, $parameters);

            // deal with permissions added to groups

            // delete first all the permissions related to groups
            while ($globalsPermission = $globalsPermissions->fetch_array(MYSQLI_ASSOC)) {
                $this->getRepository(GlobalPermission::class)->deleteByPermissionId($clientId, $globalsPermission['id'], 'group');
            }

            $date = Util::getServerCurrentDateTime();

            $requestParameters = $request->request->all();

            foreach ($requestParameters as $key => $value) {
                if (substr($key, 0, 5) == 'group') {
                    $data = explode("_", $key);
                    $globalsPermissionId = $data[1];
                    $groupId = $data[2];

                    $this->getRepository(GlobalPermission::class)->addDataForGroupId($clientId, $globalsPermissionId, $groupId, $date);
                }
            }

            // deal with permissions added to users

            // delete first all the permissions related to individual users
            while ($globalsPermission = $globalsPermissions->fetch_array(MYSQLI_ASSOC)) {
                $this->getRepository(GlobalPermission::class)->deleteByPermissionId($clientId, $globalsPermission['id'], 'user');
            }

            foreach ($requestParameters as $key => $value) {
                if (substr($key, 0, 4) == 'user') {
                    $data = explode("_", $key);
                    $globalsPermissionId = $data[1];
                    $userId = $data[2];

                    $this->getRepository(GlobalPermission::class)->addDataForUserId($clientId, $globalsPermissionId, $userId);
                }
            }

            return new RedirectResponse('/documentador/administration/global-permissions');
        }
        $documentatorSettings = $this->getRepository(UbirimiClient::class)->getDocumentadorSettings($clientId);
        $session->set('documentator/settings', $documentatorSettings);

        $users = $this->getRepository(UbirimiUser::class)->getByClientId($clientId);
        $groups = $this->getRepository(UbirimiGroup::class)->getByClientIdAndProductId($clientId, SystemProduct::SYS_PRODUCT_DOCUMENTADOR);

        return $this->render(__DIR__ . '/../../../Resources/views/administration/globalpermissions/Edit.php', get_defined_vars());
    }
}