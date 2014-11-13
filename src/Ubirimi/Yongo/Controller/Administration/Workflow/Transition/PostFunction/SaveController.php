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

namespace Ubirimi\Yongo\Controller\Administration\Workflow\Transition\PostFunction;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Workflow\Workflow;
use Ubirimi\Yongo\Repository\Workflow\WorkflowFunction;

class SaveController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $field_ids = $request->request->get('field_ids');
        $field_values = $request->request->get('field_values');
        $workflowId = $request->request->get('workflow_id');
        $IdFrom = $request->request->get('id_from');
        $IdTo = $request->request->get('id_to');
        $functionId = $field_values[0];

        $data = $this->getRepository(Workflow::class)->getDataByStepIdFromAndStepIdTo($workflowId, $IdFrom, $IdTo);

        $value = '';

        if ($functionId == WorkflowFunction::FUNCTION_SET_ISSUE_FIELD_VALUE) {
            $field_name = $field_values[1];
            $field_value = $field_values[2];
            $value = 'field_name=' . $field_name . '###field_value=' . $field_value;
        }
        $this->getRepository(WorkflowFunction::class)->addPostFunction($data['id'], $functionId, $value);
    }
}