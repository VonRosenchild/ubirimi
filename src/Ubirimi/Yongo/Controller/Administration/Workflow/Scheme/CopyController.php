<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Workflow\WorkflowScheme;

    Util::checkUserIsLoggedInAndRedirect();

    $workflowSchemeId = $_GET['id'];
    $workflowScheme = WorkflowScheme::getMetaDataById($workflowSchemeId);

    if ($workflowScheme['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    $emptyName = false;
    $duplicateName = false;

    if (isset($_POST['copy_workflow_scheme'])) {
        $name = Util::cleanRegularInputField($_POST['name']);
        $description = Util::cleanRegularInputField($_POST['description']);

        if (empty($name)) {
            $emptyName = true;
        }

        $workflowSchemeAlreadyExisting = WorkflowScheme::getByClientIdAndName($clientId, mb_strtolower($name));
        if ($workflowSchemeAlreadyExisting) {
            $duplicateName = true;
        }

        if (!$emptyName && !$workflowSchemeAlreadyExisting) {
            $copiedWorkflowScheme = new WorkflowScheme($clientId, $name, $description);

            $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));
            $copiedWorkflowSchemeId = $copiedWorkflowScheme->save($currentDate);

            $workflowSchemeData = WorkflowScheme::getDataById($workflowSchemeId);

            while ($workflowSchemeData && $data = $workflowSchemeData->fetch_array(MYSQLI_ASSOC)) {
                $copiedWorkflowScheme->addData($copiedWorkflowSchemeId, $data['workflow_id'], $currentDate);
            }

            Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'Copy Yongo Workflow Scheme ' . $workflowScheme['name'], $currentDate);

            header('Location: /yongo/administration/workflows/schemes');
        }
    }

    $menuSelectedCategory = 'issue';

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Copy Workflow Scheme';

    require_once __DIR__ . '/../../../../Resources/views/administration/workflow/scheme/Copy.php';