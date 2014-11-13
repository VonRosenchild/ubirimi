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

namespace Ubirimi\Yongo\Controller\Administration\Project;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Permission\Permission;
use Ubirimi\Yongo\Repository\Project\ProjectCategory;

class ListController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $hasGlobalAdministrationPermission = $session->get('user/yongo/is_global_administrator');
        $hasGlobalSystemAdministrationPermission = $session->get('user/yongo/is_global_system_administrator');
        $hasAdministerProjectsPermission = $session->get('user/yongo/is_global_project_administrator');

        $accessToPage = false;
        if ($hasAdministerProjectsPermission || $hasGlobalAdministrationPermission || $hasGlobalSystemAdministrationPermission) {
            $accessToPage = true;
        }

        if ($hasGlobalAdministrationPermission && $hasGlobalSystemAdministrationPermission) {
            $projects = $this->getRepository(UbirimiClient::class)->getProjects($session->get('client/id'), 'array');
        } else if ($hasAdministerProjectsPermission) {
            $projects = $this->getRepository(UbirimiClient::class)->getProjectsByPermission(
                $session->get('client/id'),
                $session->get('user/id'),
                Permission::PERM_ADMINISTER_PROJECTS,
                'array'
            );
        }

        $projectCategories = $this->getRepository(ProjectCategory::class)->getAll($session->get('client/id'));
        $menuSelectedCategory = 'project';

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Projects';

        $includeCheckbox = true;

        return $this->render(__DIR__ . '/../../../Resources/views/administration/project/List.php', get_defined_vars());
    }
}
