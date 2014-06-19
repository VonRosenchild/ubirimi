<?php
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Workflow\Workflow;

    Util::checkUserIsLoggedInAndRedirect();

    $workflowId = $_GET['id'];

    $workflowMetadata = Workflow::getMetaDataById($workflowId);

    if ($workflowMetadata['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    $workflowSteps = Workflow::getSteps($workflowId);
    $menuSelectedCategory = 'issue';

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Workflow / Text View';

    require_once __DIR__ . '/../../../Resources/views/administration/workflow/ViewAsText.php';