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

namespace Ubirimi\General\Controller\User;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Issue;

class DeleteConfirmController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $userId = $request->get('user_id');
        $delete_own_user = false;
        if ($userId == $session->get('user/id'))
            $delete_own_user = true;

        if (!$delete_own_user) {
            $issues_reported_count = 0;
            $issues_assigned_count = 0;

            $user = $this->getRepository(UbirimiUser::class)->getById($userId);
            $issuesReported = $this->getRepository(Issue::class)->getByParameters(array('reporter' => $userId));
            $issuesAssigned = $this->getRepository(Issue::class)->getByParameters(array('assignee' => $userId));

            if (null != $issuesReported)
                $issues_reported_count = $issuesReported->num_rows;

            if (null != $issuesAssigned)
                $issues_assigned_count = $issuesAssigned->num_rows;
        }

        return $this->render(__DIR__ . '/../../Resources/views/user/DeleteConfirm.php', get_defined_vars());
    }
}
