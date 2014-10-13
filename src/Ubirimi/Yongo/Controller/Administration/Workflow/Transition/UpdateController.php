<?php
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Workflow\Workflow;

    Util::checkUserIsLoggedInAndRedirect();

    $transition_name = $_POST['transition_name'];
    $workflowId = $_POST['workflow_id'];
    $screenId = isset($_POST['screen_id']) ? $_POST['screen_id'] : null;
    $idFrom = $_POST['id_from'];
    $idTo = $_POST['id_to'];

    $this->getRepository('yongo.workflow.workflow')->updateTransitionData($workflowId, $transition_name, $screenId, $idFrom, $idTo);