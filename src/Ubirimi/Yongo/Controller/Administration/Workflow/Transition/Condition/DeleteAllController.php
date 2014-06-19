<?php
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Workflow\WorkflowCondition;

    Util::checkUserIsLoggedInAndRedirect();

    $transitionId = $_POST['id'];

    WorkflowCondition::deleteByTransitionId($transitionId);