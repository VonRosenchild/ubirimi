<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueEvent;
    use Ubirimi\Yongo\Repository\Issue\IssueSettings;
    use Ubirimi\Yongo\Repository\Screen\Screen;
    use Ubirimi\Yongo\Repository\Workflow\Workflow;
    use Ubirimi\Yongo\Repository\Workflow\WorkflowFunction;

    Util::checkUserIsLoggedInAndRedirect();

    $workflowStepId = $_GET['id'];

    $workflowStep = Workflow::getStepById($workflowStepId);
    $workflowId = $workflowStep['workflow_id'];
    $steps = Workflow::getSteps($workflowId);

    $workflowMetadata = Workflow::getMetaDataById($workflowId);
    if ($workflowMetadata['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    $workflowSteps = Workflow::getSteps($workflowId);
    $statuses = IssueSettings::getAllIssueSettings('status', $clientId);
    $screens = Screen::getAll($clientId);

    $emptyName = false;

    if (isset($_POST['add_transition'])) {
        $name = Util::cleanRegularInputField($_POST['name']);
        $description = Util::cleanRegularInputField($_POST['description']);
        $step = $_POST['step'];
        $screen = $_POST['screen'];

        if (empty($name))
            $emptyName = true;

        if (!$emptyName) {
            $currentDate = Util::getServerCurrentDateTime();

            $transitionId = Workflow::addTransition($workflowId, $screen, $workflowStepId, $step, $name, $description);
            Workflow::addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_SET_ISSUE_STATUS_AS_IN_WORKFLOW_STEP, 'set_issue_status');
            Workflow::addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_UPDATE_ISSUE_CHANGE_HISTORY, 'update_issue_history');
            $eventId = IssueEvent::getByClientIdAndCode($clientId, IssueEvent::EVENT_GENERIC_CODE, 'id');
            Workflow::addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_FIRE_EVENT, 'event=' . $eventId);

            Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'ADD Yongo Workflow Transition' , $currentDate);

            header('Location: /yongo/administration/workflow/view-as-text/' . $workflowId);
        }
    }

    $menuSelectedCategory = 'issue';
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Workflow Transition';

    require_once __DIR__ . '/../../../../Resources/views/administration/workflow/step/AddTransition.php';