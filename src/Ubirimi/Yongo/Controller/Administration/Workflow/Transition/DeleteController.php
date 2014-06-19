<?php
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Workflow\Workflow;

    Util::checkUserIsLoggedInAndRedirect();

    $workflowId = $_POST['workflow_id'];
    $idFrom = $_POST['id_from'];
    $idTo = $_POST['id_to'];
    Workflow::deleteRecord($workflowId, $idFrom, $idTo);