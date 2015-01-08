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

class AddController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $workflowId = $request->get('id');

        $workflowMetadata = $this->getRepository(Workflow::class)->getMetaDataById($workflowId);

        if ($workflowMetadata['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $workflowSteps = $this->getRepository(Workflow::class)->getSteps($workflowId);
        $statuses = $this->getRepository(IssueSettings::class)->getAllIssueSettings('status', $session->get('client/id'));
        $linkedStatuses = $this->getRepository(Workflow::class)->getLinkedStatuses($workflowId, 'array', 'linked_issue_status_id');

        $addStepPossible = true;
        if (count($linkedStatuses) == $statuses->num_rows) {
            $addStepPossible = false;
        }

        $emptyName = false;
        $duplicateName = false;

        if ($request->request->has('add_step')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));

            if (empty($name)) {
                $emptyName = true;
            }

            $duplicateStep = $this->getRepository(Workflow::class)->getStepByWorkflowIdAndName($workflowId, $name);
            if ($duplicateStep) {
                $duplicateName = true;
            }

            if (!$emptyName && !$duplicateName) {
                $currentDate = $date = Util::getServerCurrentDateTime();
                $StatusId = $request->request->get('linked_status');

                $this->getRepository(Workflow::class)->addStep($workflowId, $name, $StatusId, 0, $currentDate);

                $this->getLogger()->addInfo('ADD Yongo Workflow Step ' . $name, $this->getLoggerContext());

                return new RedirectResponse('/yongo/administration/workflow/view-as-text/' . $workflowId);
            }
        }

        $menuSelectedCategory = 'issue';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Workflow Step';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/workflow/step/Add.php', get_defined_vars());
    }
}
