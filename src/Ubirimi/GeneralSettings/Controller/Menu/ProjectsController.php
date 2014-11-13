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
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Permission\GlobalPermission;
use Ubirimi\Yongo\Repository\Permission\Permission;
use Ubirimi\Yongo\Repository\Project\YongoProject;

class ProjectsController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        if (Util::checkUserIsLoggedIn()) {
            $selectedProjectId = $session->get('selected_project_id');
        } else {
            $httpHOST = Util::getHttpHost();
            $clientId = $this->getRepository(UbirimiClient::class)->getByBaseURL($httpHOST, 'array', 'id');
            $loggedInUserId = null;
            $selectedProjectId = null;
        }

        if ($session->get('selected_product_id') == SystemProduct::SYS_PRODUCT_YONGO) {
            $urlPrefix = '/yongo/project/';
            $projectsMenu = $this->getRepository(UbirimiClient::class)->getProjectsByPermission(
                $session->get('client/id'),
                $session->get('user/id'),
                Permission::PERM_BROWSE_PROJECTS,
                'array'
            );
        } else {
            $urlPrefix = '/helpdesk/customer-portal/project/';
            $projectsMenu = $this->getRepository(UbirimiClient::class)->getProjects(
                $session->get('client/id'),
                'array',
                null,
                1
            );
        }

        $selectedProjectMenu = null;
        if ($selectedProjectId) {
            $selectedProjectMenu = $this->getRepository(YongoProject::class)->getById($selectedProjectId);
        }
        $hasGlobalAdministrationPermission = $this->getRepository(UbirimiUser::class)->hasGlobalPermission(
            $session->get('client/id'),
            $session->get('user/id'),
            GlobalPermission::GLOBAL_PERMISSION_YONGO_ADMINISTRATORS
        );

        $hasGlobalSystemAdministrationPermission = $this->getRepository(UbirimiUser::class)->hasGlobalPermission(
            $session->get('client/id'),
            $session->get('user/id'),
            GlobalPermission::GLOBAL_PERMISSION_YONGO_SYSTEM_ADMINISTRATORS
        );

        return $this->render(__DIR__ . '/../../Resources/views/menu/Projects.php', get_defined_vars());
    }
}
