<?php
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Workflow\Workflow;

    Util::checkUserIsLoggedInAndRedirect();

    $workflowId = $_GET['id'];

    $workflowMetadata = Workflow::getMetaDataById($workflowId);

    $workflowData = Workflow::getDataByWorkflowId($workflowId);

    if ($workflowMetadata['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    $steps = Workflow::getSteps($workflowId, 1);
    $menuSelectedCategory = 'issue';
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Workflow';

    require_once __DIR__ . '/../../../Resources/views/administration/workflow/Design.php';