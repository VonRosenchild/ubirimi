<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueSettings;
    use Ubirimi\Yongo\Repository\Workflow\Workflow;

    Util::checkUserIsLoggedInAndRedirect();

    $stepId = $_GET['id'];
    $source = isset($_GET['source']) ? $_GET['source'] : 'step';

    $step = Workflow::getStepById($stepId);
    $workflowId = $step['workflow_id'];

    $workflow = Workflow::getMetaDataById($workflowId);
    $statuses = IssueSettings::getAllIssueSettings('status', $clientId);

    $emptyName = false;

    if (isset($_POST['edit_step'])) {
        $name = Util::cleanRegularInputField($_POST['name']);
        $StatusId = Util::cleanRegularInputField($_POST['status']);

        if (empty($name))
            $emptyName = true;

        if (!$emptyName) {
            $currentDate = Util::getServerCurrentDateTime();

            Workflow::updateStepById($stepId, $name, $StatusId, $currentDate);

            Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'UPDATE Yongo Workflow Step ' . $step['name'], $currentDate);

            if ($source == 'workflow_text')
                header('Location: /yongo/administration/workflow/view-as-text/' . $workflowId);
            else
                header('Location: /yongo/administration/workflow/view-step/' . $stepId);
        }
    }

    $menuSelectedCategory = 'issue';
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Workflow Step';

    require_once __DIR__ . '/../../../../Resources/views/administration/workflow/step/Edit.php';