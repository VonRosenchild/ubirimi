<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Workflow\Workflow;
    use Ubirimi\Yongo\Repository\Workflow\WorkflowScheme;

    Util::checkUserIsLoggedInAndRedirect();

    $Id = $_GET['id'];
    $emptyName = false;
    $workflowScheme = WorkflowScheme::getMetaDataById($Id);

    if ($workflowScheme['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    $allWorkflows = Workflow::getAllByClientId($clientId);

    $schemeWorkflows = WorkflowScheme::getDataById($Id);

    $name = $workflowScheme['name'];
    $description = $workflowScheme['description'];

    if (isset($_POST['edit_workflow_scheme'])) {
        $name = Util::cleanRegularInputField($_POST['name']);
        $description = Util::cleanRegularInputField($_POST['description']);
        $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));

        if (empty($name))
            $emptyName = true;

        if (!$emptyName) {
            WorkflowScheme::updateMetaDataById($Id, $name, $description);
            WorkflowScheme::deleteDataByWorkflowSchemeId($Id);
            foreach ($_POST as $key => $value) {
                if (substr($key, 0, 9) == 'workflow_') {
                    $workflowId = str_replace('workflow_', '', $key);
                    WorkflowScheme::addData($Id, $workflowId, $currentDate);
                }
            }

            Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'UPDATE Yongo Workflow Scheme ' . $name, $currentDate);

            header('Location: /yongo/administration/workflows/schemes');
        }
    }

    $menuSelectedCategory = 'issue';
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Workflow Scheme';
    require_once __DIR__ . '/../../../../Resources/views/administration/workflow/scheme/Configure.php';