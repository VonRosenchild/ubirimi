<?php
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Workflow\Workflow;
    use Ubirimi\Yongo\Repository\Workflow\WorkflowFunction;

    Util::checkUserIsLoggedInAndRedirect();

    $workflowDataId = $_GET['id'];
    $workflowData = Workflow::getDataById($workflowDataId);
    $workflow = Workflow::getMetaDataById($workflowData['workflow_id']);

    if ($workflow['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    $postFunctions = WorkflowFunction::getByWorkflowDataId($workflowDataId);

    $menuSelectedCategory = 'issue';
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Workflow Transition Post Function';
    require_once __DIR__ . '/../../../../../Resources/views/administration/workflow/transition/post_function/View.php';