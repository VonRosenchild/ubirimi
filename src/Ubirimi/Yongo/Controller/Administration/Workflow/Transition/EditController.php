<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Screen\Screen;
    use Ubirimi\Yongo\Repository\Workflow\Workflow;

    Util::checkUserIsLoggedInAndRedirect();

    $workflowDataId = $_GET['id'];
    $workflowData = Workflow::getDataById($workflowDataId);
    $workflowId = $workflowData['workflow_id'];
    $workflow = Workflow::getMetaDataById($workflowId);

    $screens = Screen::getAll($clientId);
    $steps = Workflow::getSteps($workflowId);

    if (isset($_POST['edit_transition'])) {
        $name = Util::cleanRegularInputField($_POST['transition_name']);
        $description = Util::cleanRegularInputField($_POST['transition_description']);
        $step = $_POST['step'];
        $screen = $_POST['screen'];
        Workflow::updateDataById($workflowData['id'], $name, $description, $screen, $step);

        $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));
        Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'UPDATE Yongo Workflow Transition ' . $name, $currentDate);

        header('Location: /yongo/administration/workflow/view-transition/' . $workflowDataId);
    }
    $menuSelectedCategory = 'issue';
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Workflow Transition';
    
    require_once __DIR__ . '/../../../../Resources/views/administration/workflow/transition/Edit.php';