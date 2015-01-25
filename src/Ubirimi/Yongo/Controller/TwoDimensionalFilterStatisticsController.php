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

namespace Ubirimi\Yongo\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\Yongo\Repository\Permission\Permission;
use Ubirimi\Yongo\Repository\Permission\GlobalPermission;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Issue\IssueSettings;

class TwoDimensionalFilterStatisticsController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        if (Util::checkUserIsLoggedIn()) {
            $clientId = $session->get('client/id');
            $issuesPerPage = $session->get('user/issues_per_page');
            $clientSettings = $session->get('client/settings');;
        } else {
            $clientId = $this->getRepository(UbirimiClient::class)->getClientIdAnonymous();
            $issuesPerPage = 25;
            $clientSettings = $this->getRepository(UbirimiClient::class)->getSettings($clientId);
        }
        $sectionPageTitle = $clientSettings['title_name'] . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / 2 Dimensional Filter Statistics';

        $client = $this->getRepository(UbirimiClient::class)->getById($clientId);
        $allProjects = $this->getRepository(UbirimiClient::class)->getProjects($clientId);
        $menuSelectedCategory = 'home';
        $section = '2-dimensional-filter-statistics';

        $projects = $this->getRepository(UbirimiClient::class)->getProjectsByPermission(
            $clientId,
            $session->get('user/id'),
            Permission::PERM_BROWSE_PROJECTS,
            'array'
        );

        $projectIdsArray = array();
        $projectIdsNames = array();
        for ($i = 0; $i < count($projects); $i++) {
            $projectIdsArray[] = $projects[$i]['id'];
            $projectIdsNames[] = array($projects[$i]['id'], $projects[$i]['name']);
        }

        $hasGlobalAdministrationPermission = $this->getRepository(UbirimiUser::class)->hasGlobalPermission(
            $clientId,
            $session->get('user/id'),
            GlobalPermission::GLOBAL_PERMISSION_YONGO_ADMINISTRATORS
        );

        $hasGlobalSystemAdministrationPermission = $this->getRepository(UbirimiUser::class)->hasGlobalPermission(
            $clientId,
            $session->get('user/id'),
            GlobalPermission::GLOBAL_PERMISSION_YONGO_SYSTEM_ADMINISTRATORS
        );

        $twoDimensionalData = null;
        if (count($projectIdsArray))
            $twoDimensionalData = $this->getRepository(Issue::class)->get2DimensionalFilter(-1, 'array');

        $issueStatuses = $this->getRepository(IssueSettings::class)->getAllIssueSettings('status', $clientId, 'array');
        $usersAsAssignee = $this->getRepository(UbirimiUser::class)->getByClientId($clientId);

        return $this->render(__DIR__ . '/../Resources/views/TwoDimensionalFilterStatistics.php', get_defined_vars());
    }
}
