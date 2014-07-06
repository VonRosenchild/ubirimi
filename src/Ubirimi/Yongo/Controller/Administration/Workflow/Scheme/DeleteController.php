<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Workflow\WorkflowScheme;

    Util::checkUserIsLoggedInAndRedirect();

    $Id = $_POST['id'];
    $workflowScheme = WorkflowScheme::getMetaDataById($Id);
    WorkflowScheme::deleteDataByWorkflowSchemeId($Id);
    WorkflowScheme::deleteById($Id);

    $currentDate = Util::getServerCurrentDateTime();
    Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'DELETE Yongo Workflow Scheme ' . $workflowScheme['name'], $currentDate);