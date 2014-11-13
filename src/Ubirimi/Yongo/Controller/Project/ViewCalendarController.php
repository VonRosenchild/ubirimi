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
use Ubirimi\Yongo\Repository\Permission\GlobalPermission;
use Ubirimi\Yongo\Repository\Permission\Permission;
use Ubirimi\Yongo\Repository\Project\YongoProject;

class ViewCalendarController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        if (Util::checkUserIsLoggedIn()) {
            $clientId = $session->get('client/id');
            $loggedInUserId = $session->get('user/id');
            $clientSettings = $session->get('client/settings');
        } else {
            $clientId = $this->getRepository(UbirimiClient::class)->getClientIdAnonymous();
            $loggedInUserId = null;
            $clientSettings = $this->getRepository(UbirimiClient::class)->getSettings($clientId);
        }

        $projectId = $request->get('id');
        $month = $request->get('month');
        $year = $request->get('year');

        $project = $this->getRepository(YongoProject::class)->getById($projectId);

        if ($project['client_id'] != $clientId) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $session->set('selected_project_id', $projectId);
        $menuSelectedCategory = 'project';

        $hasGlobalAdministrationPermission = $this->getRepository(UbirimiUser::class)->hasGlobalPermission($clientId, $loggedInUserId, GlobalPermission::GLOBAL_PERMISSION_YONGO_ADMINISTRATORS);
        $hasGlobalSystemAdministrationPermission = $this->getRepository(UbirimiUser::class)->hasGlobalPermission($clientId, $loggedInUserId, GlobalPermission::GLOBAL_PERMISSION_YONGO_SYSTEM_ADMINISTRATORS);
        $hasAdministerProjectsPermission = $this->getRepository(UbirimiClient::class)->getProjectsByPermission($clientId, $loggedInUserId, Permission::PERM_ADMINISTER_PROJECTS);

        $hasAdministerProject = $hasGlobalSystemAdministrationPermission || $hasGlobalAdministrationPermission || $hasAdministerProjectsPermission;

        $previousMonthName = date("F", mktime(0, 0, 0, $month - 1));
        $currentMonthName = date("F", mktime(0, 0, 0, $month));
        $nextMonthName = date("F", mktime(0, 0, 0, $month + 1));

        $sectionPageTitle = $clientSettings['title_name'] . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / ' . $project['name'] . ' / Calendar (due date)';

        return $this->render(__DIR__ . '/../../Resources/views/project/ViewCalendar.php', get_defined_vars());
    }
}
