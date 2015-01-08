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

namespace Ubirimi\Yongo\Controller\Administration\Project\Component;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Project\YongoProject;


class AddSubcomponentController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $projectId = $request->get('project_id');
        $parentComponentId = $request->get('id');
        $parentComponent = $this->getRepository(YongoProject::class)->getComponentById($parentComponentId);

        $project = $this->getRepository(YongoProject::class)->getById($projectId);

        if ($project['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }
        $users = $this->getRepository(UbirimiClient::class)->getUsers($session->get('client/id'));

        $emptyName = false;
        $alreadyExists = false;

        if ($request->request->has('confirm_new_component')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));
            $leader = Util::cleanRegularInputField($request->request->get('leader'));
            $postParentComponentId = $request->request->get('parent_component_id');

            if (empty($name))
                $emptyName = true;

            $components_duplicate = $this->getRepository(YongoProject::class)->getComponentByName($projectId, $name);
            if ($components_duplicate)
                $alreadyExists = true;

            if (!$emptyName && !$alreadyExists) {
                if ($leader == -1) {
                    $leader = null;
                }
                $currentDate = Util::getServerCurrentDateTime();
                $this->getRepository(YongoProject::class)->addComponent($projectId, $name, $description, $leader, $postParentComponentId, $currentDate);

                $this->getLogger()->addInfo('ADD Project Sub-Component ' . $name, $this->getLoggerContext());

                return new RedirectResponse('/yongo/administration/project/components/' . $projectId);
            }
        }
        $menuSelectedCategory = 'project';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Project Sub-Component';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/project/component/AddSubcomponent.php', get_defined_vars());
    }
}
