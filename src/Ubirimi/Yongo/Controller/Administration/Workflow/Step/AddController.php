<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueSettings;
    use Ubirimi\Yongo\Repository\Workflow\Workflow;

    Util::checkUserIsLoggedInAndRedirect();

    $workflowId = $_GET['id'];

    $workflowMetadata = Workflow::getMetaDataById($workflowId);

    if ($workflowMetadata['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }
    $workflowSteps = Workflow::getSteps($workflowId);
    $statuses = IssueSettings::getAllIssueSettings('status', $clientId);
    $linkedStatuses = Workflow::getLinkedStatuses($workflowId, 'array', 'linked_issue_status_id');

    $addStepPossible = true;
    if (count($linkedStatuses) == $statuses->num_rows)
        $addStepPossible = false;

    $emptyName = false;
    $duplicateName = false;

    if (isset($_POST['add_step'])) {
        $name = Util::cleanRegularInputField($_POST['name']);

        if (empty($name))
            $emptyName = true;

        $duplicateStep = Workflow::getStepByWorkflowIdAndName($workflowId, $name);
        if ($duplicateStep)
            $duplicateName = true;

        if (!$emptyName && !$duplicateName) {
            $currentDate = $date = Util::getCurrentDateTime($session->get('client/settings/timezone'));
            $StatusId = $_POST['linked_status'];

            Workflow::addStep($workflowId, $name, $StatusId, $currentDate);

            Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'ADD Yongo Workflow Step ' . $name, $currentDate);

            header('Location: /yongo/administration/workflow/view-as-text/' . $workflowId);
        }
    }

    $menuSelectedCategory = 'issue';
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Workflow Step';

    require_once __DIR__ . '/../../../../Resources/views/administration/workflow/step/Add.php';