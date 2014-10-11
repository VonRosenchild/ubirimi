<?php
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Workflow\Workflow;
    use Ubirimi\Yongo\Repository\Workflow\Condition;

    Util::checkUserIsLoggedInAndRedirect();

    $transitionId = $_POST['transition_id'];
    $type = $_POST['type'];

    $conditionData = Condition::getByTransitionId($transitionId);
    if (!$conditionData)
        Workflow::addCondition($transitionId, '');

    if ($type == 'open_bracket')
        Condition::addConditionString($transitionId, '(');
    else if ($type == 'closed_bracket')
        Condition::addConditionString($transitionId, ')');
    else if ($type == 'operator_and')

        Condition::addConditionString($transitionId, '[[AND]]');
    else if ($type == 'operator_or')
        Condition::addConditionString($transitionId, '[[OR]]');