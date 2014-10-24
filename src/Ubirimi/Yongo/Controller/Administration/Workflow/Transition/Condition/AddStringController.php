<?php

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