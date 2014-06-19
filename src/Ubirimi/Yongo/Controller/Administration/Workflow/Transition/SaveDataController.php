<?php
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Workflow\Workflow;

    Util::checkUserIsLoggedInAndRedirect();

    $project_workflow_id = $_POST['project_workflow_id'];
    $idFrom = $_POST['id_from'];
    $idTo = $_POST['id_to'];
    $name = $_POST['name'];

    Workflow::createNewSingleDataRecord($project_workflow_id, $idFrom, $idTo, $name);