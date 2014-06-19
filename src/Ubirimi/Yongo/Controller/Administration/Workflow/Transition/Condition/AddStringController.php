<?php
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Workflow\Workflow;
    use Ubirimi\Yongo\Repository\Workflow\WorkflowCondition;

    Util::checkUserIsLoggedInAndRedirect();

    $transitionId = $_POST['transition_id'];
    $type = $_POST['type'];

    $conditionData = WorkflowCondition::getByTransitionId($transitionId);
    if (!$conditionData)
        Workflow::addCondition($transitionId, '');

    if ($type == 'open_bracket')
        WorkflowCondition::addConditionString($transitionId, '(');
    else if ($type == 'closed_bracket')
        WorkflowCondition::addConditionString($transitionId, ')');
    else if ($type == 'operator_and')

        WorkflowCondition::addConditionString($transitionId, '[[AND]]');
    else if ($type == 'operator_or')
        WorkflowCondition::addConditionString($transitionId, '[[OR]]');