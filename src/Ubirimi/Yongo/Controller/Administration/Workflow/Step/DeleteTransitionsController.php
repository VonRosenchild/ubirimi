<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Workflow\Workflow;

    Util::checkUserIsLoggedInAndRedirect();

    $stepId = $_GET['id'];

    $step = Workflow::getStepById($stepId);
    $workflowId = $step['workflow_id'];

    $workflowMetadata = Workflow::getMetaDataById($workflowId);

    if ($workflowMetadata['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    $transitions = Workflow::getOutgoingTransitionsForStep($workflowId, $stepId);

    if (isset($_POST['delete_transitions'])) {
        $transitionsPosted = $_POST['transitions'];

        Workflow::deleteTransitions($workflowId, $transitionsPosted);

        $currentDate = Util::getServerCurrentDateTime();
        Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'DELETE Yongo Workflow Transition', $currentDate);

        header('Location: /yongo/administration/workflow/view-as-text/' . $workflowId);
    }

    $menuSelectedCategory = 'issue';

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Delete Transitions';

    require_once __DIR__ . '/../../../../Resources/views/administration/workflow/step/DeleteTransitions.php';