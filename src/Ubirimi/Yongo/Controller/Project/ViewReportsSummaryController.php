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

namespace Ubirimi\Yongo\Controller\Project;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Permission\GlobalPermission;
use Ubirimi\Yongo\Repository\Permission\Permission;
use Ubirimi\Yongo\Repository\Project\YongoProject;

class ViewReportsSummaryController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        if (Util::checkUserIsLoggedIn()) {
            $loggedInUserId = $session->get('user/id');
            $clientId = $session->get('client/id');
            $clientSettings = $session->get('client/settings');
        } else {
            $loggedInUserId = null;
            $clientId = $this->getRepository(UbirimiClient::class)->getClientIdAnonymous();
            $clientSettings = $this->getRepository(UbirimiClient::class)->getSettings($clientId);
        }

        $projectId = $request->get('id');
        $project = $this->getRepository(YongoProject::class)->getById($projectId);

        if ($project['client_id'] != $clientId) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $currentDate = Util::getServerCurrentDateTime();

        $dateFrom = date_create(Util::getServerCurrentDateTime());
        $dateTo = date_format(date_create(Util::getServerCurrentDateTime()), 'Y-m-d');

        date_sub($dateFrom, date_interval_create_from_date_string('1 months'));
        $dateFrom = date_format($dateFrom, 'Y-m-d');

        $issueQueryParameters = array('project' => array($projectId), 'resolution' => array(-2));
        $issues = $this->getRepository(Issue::class)->getByParameters($issueQueryParameters, $loggedInUserId, null, $loggedInUserId);

        $hasGlobalAdministrationPermission = $this->getRepository(UbirimiUser::class)->hasGlobalPermission($clientId, $loggedInUserId, GlobalPermission::GLOBAL_PERMISSION_YONGO_ADMINISTRATORS);
        $hasGlobalSystemAdministrationPermission = $this->getRepository(UbirimiUser::class)->hasGlobalPermission($clientId, $loggedInUserId, GlobalPermission::GLOBAL_PERMISSION_YONGO_SYSTEM_ADMINISTRATORS);
        $hasAdministerProjectsPermission = $this->getRepository(UbirimiClient::class)->getProjectsByPermission($clientId, $loggedInUserId, Permission::PERM_ADMINISTER_PROJECTS);

        $hasAdministerProject = $hasGlobalSystemAdministrationPermission || $hasGlobalAdministrationPermission || $hasAdministerProjectsPermission;

        $sectionPageTitle = $clientSettings['title_name'] . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / ' . $project['name'] . ' / Reports';

        $menuSelectedCategory = 'project';

        return $this->render(__DIR__ . '/../../Resources/views/project/ViewReportsSummary.php', get_defined_vars());
    }
}
