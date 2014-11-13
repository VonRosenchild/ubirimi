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

namespace Ubirimi\Yongo\Controller\Administration\Workflow\Step;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\IssueSettings;
use Ubirimi\Yongo\Repository\Workflow\Workflow;

class EditController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $stepId = $request->get('id');
        $source = $request->get('source', 'step');

        $step = $this->getRepository(Workflow::class)->getStepById($stepId);
        $workflowId = $step['workflow_id'];

        $workflow = $this->getRepository(Workflow::class)->getMetaDataById($workflowId);
        $statuses = $this->getRepository(IssueSettings::class)->getAllIssueSettings('status', $session->get('client/id'));

        $emptyName = false;

        if ($request->request->has('edit_step')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $StatusId = Util::cleanRegularInputField($request->request->get('status'));

            if (empty($name))
                $emptyName = true;

            if (!$emptyName) {
                $currentDate = Util::getServerCurrentDateTime();

                $this->getRepository(Workflow::class)->updateStepById($stepId, $name, $StatusId, $currentDate);

                $this->getRepository(UbirimiLog::class)->add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_YONGO,
                    $session->get('user/id'),
                    'UPDATE Yongo Workflow Step ' . $step['name'],
                    $currentDate
                );

                if ($source == 'workflow_text') {
                    return new RedirectResponse('/yongo/administration/workflow/view-as-text/' . $workflowId);
                }

                return new RedirectResponse('/yongo/administration/workflow/view-step/' . $stepId);
            }
        }

        $menuSelectedCategory = 'issue';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Workflow Step';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/workflow/step/Edit.php', get_defined_vars());
    }
}
