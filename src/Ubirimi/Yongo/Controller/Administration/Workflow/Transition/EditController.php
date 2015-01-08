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

namespace Ubirimi\Yongo\Controller\Administration\Workflow\Transition;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Screen\Screen;
use Ubirimi\Yongo\Repository\Workflow\Workflow;

class EditController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $workflowDataId = $request->get('id');
        $workflowData = $this->getRepository(Workflow::class)->getDataById($workflowDataId);
        $workflowId = $workflowData['workflow_id'];
        $workflow = $this->getRepository(Workflow::class)->getMetaDataById($workflowId);

        $screens = $this->getRepository(Screen::class)->getAll($session->get('client/id'));
        $steps = $this->getRepository(Workflow::class)->getSteps($workflowId);

        if ($request->request->has('edit_transition')) {
            $name = Util::cleanRegularInputField($request->request->get('transition_name'));
            $description = Util::cleanRegularInputField($request->request->get('transition_description'));
            $step = $request->request->get('step');
            $screen = $request->request->get('screen');
            $this->getRepository(Workflow::class)->updateDataById($workflowData['id'], $name, $description, $screen, $step);

            $currentDate = Util::getServerCurrentDateTime();
            $this->getLogger()->addInfo('UPDATE Yongo Workflow Transition ' . $name, $this->getLoggerContext());

            return new RedirectResponse('/yongo/administration/workflow/view-transition/' . $workflowDataId);
        }

        $menuSelectedCategory = 'issue';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Workflow Transition';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/workflow/transition/Edit.php', get_defined_vars());
    }
}
