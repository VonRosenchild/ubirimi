<?php
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Workflow\Workflow;

    Util::checkUserIsLoggedInAndRedirect();

    $stepId = $_GET['id'];
    $step = $this->getRepository('yongo.workflow.workflow')->getStepById($stepId);
    $workflowId = $step['workflow_id'];

    $workflow = $this->getRepository('yongo.workflow.workflow')->getMetaDataById($workflowId);

    $menuSelectedCategory = 'issue';
    $stepProperties = $this->getRepository('yongo.workflow.workflow')->getStepProperties($stepId);

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Workflow Step Properties';

    require_once __DIR__ . '/../../../../../Resources/views/administration/workflow/step/property/List.php';