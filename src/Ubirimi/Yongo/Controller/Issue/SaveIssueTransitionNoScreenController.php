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

namespace Ubirimi\Yongo\Controller\Issue;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\Email\Email;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Workflow\Workflow;
use Ubirimi\Yongo\Repository\Workflow\WorkflowFunction;

class SaveIssueTransitionNoScreenController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {

        Util::checkUserIsLoggedInAndRedirect();

        $clientId = UbirimiContainer::get()['session']->get('client/id');
        $loggedInUserId = UbirimiContainer::get()['session']->get('user/id');

        $workflowStepIdFrom = $request->request->get('step_id_from');
        $workflowStepIdTo = $request->request->get('step_id_to');
        $workflowId = $request->request->get('workflow_id');
        $issueId = $request->request->get('issue_id');

        $clientSettings = $this->getRepository(UbirimiClient::class)->getSettings($clientId);

        $workflowData = $this->getRepository(Workflow::class)->getDataByStepIdFromAndStepIdTo($workflowId, $workflowStepIdFrom, $workflowStepIdTo);
        $issue = $this->getRepository(Issue::class)->getByParameters(array('issue_id' => $issueId), $loggedInUserId);

        $canBeExecuted = $this->getRepository(Workflow::class)->checkConditionsByTransitionId($workflowData['id'], $loggedInUserId, $issue);

        if ($canBeExecuted) {

            $smtpSettings = $session->get('client/settings/smtp');
            if ($smtpSettings) {
                Email::$smtpSettings = $smtpSettings;
            }

            $date = Util::getServerCurrentDateTime();
            $this->getRepository(WorkflowFunction::class)->triggerPostFunctions($clientId, $issue, $workflowData, array(), $loggedInUserId, $date);

            // update the date_updated field
            $this->getRepository(Issue::class)->updateById($issueId, array('date_updated' => $date), $date);

            return new Response('success');

        } else {
            return new Response('can_not_be_executed');
        }
    }
}