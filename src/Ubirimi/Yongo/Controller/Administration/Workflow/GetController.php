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

namespace Ubirimi\Yongo\Controller\Administration\Workflow;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Workflow\Workflow;
use Ubirimi\Yongo\Repository\Workflow\WorkflowPosition;

class GetController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $workflowId = $request->request->get('id');
        $workflowData = $this->getRepository(Workflow::class)->getDataByWorkflowId($workflowId);

        $result = array();
        if ($workflowData) {
            while ($workflow = $workflowData->fetch_array(MYSQLI_ASSOC)) {
                $result[] = $workflow;
            }
        }

        $positions = array();

        $position_result = $this->getRepository(WorkflowPosition::class)->getByWorkflowId($workflowId);
        if ($position_result) {
            while ($position = $position_result->fetch_array(MYSQLI_ASSOC)) {
                $positions[] = $position;
            }
        }

        $finalResult = array('values' => $result, 'positions' => $positions);

        return new Response(json_encode($finalResult));
    }
}