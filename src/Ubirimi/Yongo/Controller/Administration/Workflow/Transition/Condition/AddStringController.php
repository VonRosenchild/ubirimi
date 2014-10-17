<?php

namespace Ubirimi\Yongo\Controller\Administration\Workflow\Transition\Condition;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Workflow\Workflow;

class AddStringController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $transitionId = $request->request->get('transition_id');
        $type = $request->request->get('type');

        $conditionData = $this->getRepository('yongo.workflow.condition')->getByTransitionId($transitionId);
        if (!$conditionData)
            $this->getRepository('yongo.workflow.workflow')->addCondition($transitionId, '');

        if ($type == 'open_bracket')
            $this->getRepository('yongo.workflow.condition')->addConditionString($transitionId, '(');
        else if ($type == 'closed_bracket')
            $this->getRepository('yongo.workflow.condition')->addConditionString($transitionId, ')');
        else if ($type == 'operator_and')

            $this->getRepository('yongo.workflow.condition')->addConditionString($transitionId, '[[AND]]');
        else if ($type == 'operator_or')
        $this->getRepository('yongo.workflow.condition')->addConditionString($transitionId, '[[OR]]');
    }
}