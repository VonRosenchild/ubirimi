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

namespace Ubirimi\Yongo\Controller\Administration\Group;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\Repository\User\UbirimiGroup;
use Ubirimi\UbirimiController;
use Ubirimi\Util;


class AssignUsersConfirmController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $groupId = $request->get('group_id');

        $group = $this->getRepository(UbirimiGroup::class)->getMetadataById($groupId);
        $allUsers = $this->getRepository(UbirimiClient::class)->getUsers($session->get('client/id'));
        $groupUsers = $this->getRepository(UbirimiGroup::class)->getDataByGroupId($groupId);

        $groupUsersArrayIds = array();

        while ($groupUsers && $user = $groupUsers->fetch_array(MYSQLI_ASSOC)) {
            $groupUsersArrayIds[] = $user['user_id'];
        }

        if ($groupUsers) {
            $groupUsers->data_seek(0);
        }

        $firstSelected = true;

        return $this->render(__DIR__ . '/../../../Resources/views/administration/group/AssignUsersConfirm.php', get_defined_vars());
    }
}