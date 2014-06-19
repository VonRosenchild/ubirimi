<?php
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Workflow\WorkflowFunction;

    Util::checkUserIsLoggedInAndRedirect();

    $postFunctionDataId = $_POST['id'];
    WorkflowFunction::deleteByPostFunctionDataId($postFunctionDataId);