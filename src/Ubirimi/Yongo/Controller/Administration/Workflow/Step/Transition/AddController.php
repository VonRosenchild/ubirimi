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

namespace Ubirimi\Yongo\Controller\Administration\Workflow\Step\Transition;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\IssueEvent;
use Ubirimi\Yongo\Repository\Issue\IssueSettings;
use Ubirimi\Yongo\Repository\Screen\Screen;
use Ubirimi\Yongo\Repository\Workflow\Workflow;
use Ubirimi\Yongo\Repository\Workflow\WorkflowFunction;

class AddController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $workflowStepId = $request->get('id');

        $workflowStep = $this->getRepository(Workflow::class)->getStepById($workflowStepId);
        $workflowId = $workflowStep['workflow_id'];
        $steps = $this->getRepository(Workflow::class)->getSteps($workflowId);

        $workflowMetadata = $this->getRepository(Workflow::class)->getMetaDataById($workflowId);
        if ($workflowMetadata['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $workflowSteps = $this->getRepository(Workflow::class)->getSteps($workflowId);
        $statuses = $this->getRepository(IssueSettings::class)->getAllIssueSettings('status', $session->get('client/id'));
        $screens = $this->getRepository(Screen::class)->getAll($session->get('client/id'));

        $emptyName = false;

        if ($request->request->has('add_transition')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));
            $step = $request->request->get('step');
            $screen = $request->request->get('screen');

            if (empty($name))
                $emptyName = true;

            if (!$emptyName) {
                $currentDate = Util::getServerCurrentDateTime();

                $transitionId = $this->getRepository(Workflow::class)->addTransition(
                    $workflowId,
                    $screen,
                    $workflowStepId,
                    $step,
                    $name,
                    $description
                );

                $this->getRepository(Workflow::class)->addPostFunctionToTransition(
                    $transitionId,
                    WorkflowFunction::FUNCTION_SET_ISSUE_STATUS_AS_IN_WORKFLOW_STEP,
                    'set_issue_status'
                );

                $this->getRepository(Workflow::class)->addPostFunctionToTransition(
                    $transitionId,
                    WorkflowFunction::FUNCTION_UPDATE_ISSUE_CHANGE_HISTORY,
                    'update_issue_history'
                );

                $eventId = $this->getRepository(IssueEvent::class)->getByClientIdAndCode(
                    $session->get('client/id'),
                    IssueEvent::EVENT_GENERIC_CODE,
                    'id'
                );

                $this->getRepository(Workflow::class)->addPostFunctionToTransition(
                    $transitionId,
                    WorkflowFunction::FUNCTION_FIRE_EVENT,
                    'event=' . $eventId
                );

                $this->getLogger()->addInfo('ADD Yongo Workflow Transition', $this->getLoggerContext());

                return new RedirectResponse('/yongo/administration/workflow/view-as-text/' . $workflowId);
            }
        }

        $menuSelectedCategory = 'issue';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Workflow Transition';

        return $this->render(__DIR__ . '/../../../../../Resources/views/administration/workflow/step/transition/Add.php', get_defined_vars());
    }
}
