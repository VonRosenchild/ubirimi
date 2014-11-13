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

namespace Ubirimi\Yongo\Controller\Chart;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Issue\IssueSettings;
use Ubirimi\Yongo\Repository\Permission\Permission;

class ViewTwoDimensionalFilterController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        if (Util::checkUserIsLoggedIn()) {
            $clientId = $session->get('client/id');
            $loggedInUserId = $session->get('user/id');
        } else {
            $clientId = $this->getRepository(UbirimiClient::class)->getClientIdAnonymous();
            $loggedInUserId = null;
        }
        $projects = $this->getRepository(UbirimiClient::class)->getProjectsByPermission($clientId, $loggedInUserId, Permission::PERM_BROWSE_PROJECTS, 'array');

        $projectId = $request->request->get('id');

        $projectIdsNames = array();
        for ($i = 0; $i < count($projects); $i++) {
            $projectIdsNames[] = array($projects[$i]['id'], $projects[$i]['name']);
        }

        $usersAsAssignee = $this->getRepository(UbirimiUser::class)->getByClientId($clientId);
        $issueStatuses = $this->getRepository(IssueSettings::class)->getAllIssueSettings('status', $clientId, 'array');

        $twoDimensionalData = $this->getRepository(Issue::class)->get2DimensionalFilter($projectId, 'array');

        return $this->render(__DIR__ . '/../../Resources/views/charts/ViewTwoDimensionalFilter.php', get_defined_vars());
    }
}
