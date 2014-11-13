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

namespace Ubirimi\Yongo\Controller\Administration\Workflow\Transition\Condition;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Workflow\Workflow;
use Ubirimi\Yongo\Repository\Workflow\WorkflowCondition;

class AddController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientId = $session->get('client/id');

        $workflowDataId = $request->get('id');
        $workflowData = $this->getRepository(Workflow::class)->getDataById($workflowDataId);
        $workflow = $this->getRepository(Workflow::class)->getMetaDataById($workflowData['workflow_id']);

        if ($workflow['client_id'] != $clientId) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }
        $conditions = $this->getRepository(WorkflowCondition::class)->getAll();
        $menuSelectedCategory = 'issue';
        $checkedHTML = 'checked="checked"';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Workflow Condition';

        return $this->render(__DIR__ . '/../../../../../Resources/views/administration/workflow/transition/condition/Add.php', get_defined_vars());
    }
}