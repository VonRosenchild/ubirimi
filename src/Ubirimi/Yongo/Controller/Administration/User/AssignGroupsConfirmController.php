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

namespace Ubirimi\Yongo\Controller\Administration\User;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\User\UbirimiGroup;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\UbirimiController;
use Ubirimi\Util;


class AssignGroupsConfirmController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $userId = $request->get('user_id');
        $productId = $request->get('product_id');

        $user = $this->getRepository(UbirimiUser::class)->getById($userId);
        $allProductGroups = $this->getRepository(UbirimiGroup::class)->getByClientIdAndProductId($session->get('client/id'), $productId);
        $userGroups = $this->getRepository(UbirimiGroup::class)->getByUserIdAndProductId($userId, $productId);

        $user_groups_ids_arr = array();

        while ($userGroups && $group = $userGroups->fetch_array(MYSQLI_ASSOC))
            $user_groups_ids_arr[] = $group['id'];

        if ($userGroups)
            $userGroups->data_seek(0);

        $firstSelected = true;

        return $this->render(__DIR__ . '/../../../Resources/views/administration/user/AssignGroupsConfirm.php', get_defined_vars());
    }
}
