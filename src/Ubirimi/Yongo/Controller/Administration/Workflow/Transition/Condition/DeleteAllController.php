<?php
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Workflow\Condition;

    Util::checkUserIsLoggedInAndRedirect();

    $transitionId = $_POST['id'];

    Condition::deleteByTransitionId($transitionId);