<?php
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Workflow\Workflow;

    Util::checkUserIsLoggedInAndRedirect();

    $workflowDataId = $_GET['id'];

    $workflowData = Workflow::getDataById($workflowDataId);
    $workflow = Workflow::getMetaDataById($workflowData['workflow_id']);

    if ($workflow['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    $menuSelectedCategory = 'issue';
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Workflow Transition Browser';
    require_once __DIR__ . '/../../../../Resources/views/administration/workflow/transition/ViewTransitionBrowser.php';