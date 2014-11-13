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

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Workflow\Workflow;
use Ubirimi\Yongo\Repository\Workflow\WorkflowCondition;

class AddStringController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $transitionId = $request->request->get('transition_id');
        $type = $request->request->get('type');

        $conditionData = $this->getRepository(WorkflowCondition::class)->getByTransitionId($transitionId);
        if (!$conditionData)
            $this->getRepository(Workflow::class)->addCondition($transitionId, '');

        if ($type == 'open_bracket')
            $this->getRepository(WorkflowCondition::class)->addConditionString($transitionId, '(');
        else if ($type == 'closed_bracket')
            $this->getRepository(WorkflowCondition::class)->addConditionString($transitionId, ')');
        else if ($type == 'operator_and')

            $this->getRepository(WorkflowCondition::class)->addConditionString($transitionId, '[[AND]]');
        else if ($type == 'operator_or')
        $this->getRepository(WorkflowCondition::class)->addConditionString($transitionId, '[[OR]]');
    }
}