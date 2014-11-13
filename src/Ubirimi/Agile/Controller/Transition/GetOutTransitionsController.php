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

namespace Ubirimi\Agile\Controller\Transition;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Workflow\Workflow;

class GetOutTransitionsController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $workflowId = $request->request->get('workflow_id');
        $stepIdFrom = $request->request->get('step_id_from');

        $issueId = $request->request->get('issue_id');
        $projectId = $request->request->get('project_id');

        $issueQueryParameters = array('issue_id' => $issueId);
        $issue = $this->getRepository(Issue::class)->getByParameters($issueQueryParameters, $session->get('user/id'));

        $transitions = $this->getRepository(Workflow::class)->getOutgoingTransitionsForStep($workflowId, $stepIdFrom, 'array');

        // for each transition determine if the conditions allow it to be executed
        $transitionsToBeExecuted = array();
        for ($i = 0; $i < count($transitions); $i++) {
            $canBeExecuted = $this->getRepository(Workflow::class)->checkConditionsByTransitionId(
                $transitions[$i]['id'],
                $session->get('user/id'),
                $issue
            );

            if ($canBeExecuted)
                $transitionsToBeExecuted[] = $transitions[$i];
        }

        return new Response(json_encode($transitionsToBeExecuted));
    }
}
