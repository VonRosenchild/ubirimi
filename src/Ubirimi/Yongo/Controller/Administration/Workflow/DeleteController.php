<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Workflow\Workflow;

    Util::checkUserIsLoggedInAndRedirect();

    $workflowId = $_POST['id'];

    $workflow = Workflow::getMetaDataById($workflowId);

    Workflow::deleteById($workflowId);

    $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));

    Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'DELETE Yongo Workflow ' . $workflow['name'], $currentDate);