<?php
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Workflow\WorkflowPosition;

    Util::checkUserIsLoggedInAndRedirect();

    $Id = $_POST['id'];
    $positions = $_POST['positions'];

    $good_positions = array();
    for ($i = 0; $i < count($positions); $i++) {
        $values = explode('###', $positions[$i]);
        $good_positions[] = $values;
    }
    WorkflowPosition::deleteByWorkflowId($Id);

    WorkflowPosition::addPosition($Id, $good_positions);