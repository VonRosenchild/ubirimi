<?php
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Workflow\Workflow;

    Util::checkUserIsLoggedInAndRedirect();

    $stepId = $_GET['id'];
    $step = Workflow::getStepById($stepId);
    $workflowId = $step['workflow_id'];

    $workflow = Workflow::getMetaDataById($workflowId);

    $menuSelectedCategory = 'issue';
    $stepProperties = Workflow::getStepProperties($stepId);

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Workflow Step Properties';

    require_once __DIR__ . '/../../../../../Resources/views/administration/workflow/step/property/List.php';