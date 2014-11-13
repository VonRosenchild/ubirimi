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

namespace Ubirimi\General\Controller\Menu;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Permission\Permission;
use Ubirimi\Yongo\Repository\Project\YongoProject;

class IssuesController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        if (Util::checkUserIsLoggedIn()) {

        } else {
            $httpHOST = Util::getHttpHost();
            $clientId = $this->getRepository(UbirimiClient::class)->getByBaseURL($httpHOST, 'array', 'id');
            $loggedInUserId = null;
        }

        $projectsMenu = $this->getRepository(UbirimiClient::class)->getProjectsByPermission(
            $session->get('client/id'),
            $session->get('user/id'),
            Permission::PERM_BROWSE_PROJECTS,
            'array'
        );

        $projectsForBrowsing = array();
        for ($i = 0; $i < count($projectsMenu); $i++)
            $projectsForBrowsing[$i] = $projectsMenu[$i]['id'];

        $hasCreateIssuePermission = false;
        if (count($projectsForBrowsing)) {
            $hasCreateIssuePermission = $this->getRepository(YongoProject::class)->userHasPermission(
                $projectsForBrowsing,
                Permission::PERM_CREATE_ISSUE,
                $session->get('user/id')
            );
        }

        $recentIssues = $session->get('yongo/recent_issues');

        return $this->render(__DIR__ . '/../../Resources/views/menu/Issues.php', get_defined_vars());
    }
}
