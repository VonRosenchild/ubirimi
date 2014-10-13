<?php
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Workflow\Workflow;

    Util::checkUserIsLoggedInAndRedirect();

    $stepId = $_POST['id'];

    $this->getRepository('yongo.workflow.workflow')->deleteStepById($stepId);