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

        $transitionId = $_POST['transition_id'];
        $type = $_POST['type'];

        $conditionData = Condition::getByTransitionId($transitionId);
        if (!$conditionData)
            $this->getRepository('yongo.workflow.workflow')->addCondition($transitionId, '');

        if ($type == 'open_bracket')
            Condition::addConditionString($transitionId, '(');
        else if ($type == 'closed_bracket')
            Condition::addConditionString($transitionId, ')');
        else if ($type == 'operator_and')

            Condition::addConditionString($transitionId, '[[AND]]');
        else if ($type == 'operator_or')
            Condition::addConditionString($transitionId, '[[OR]]');
    }
}