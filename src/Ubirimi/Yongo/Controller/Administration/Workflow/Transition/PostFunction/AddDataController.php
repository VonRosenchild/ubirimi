<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Workflow\Workflow;
    use Ubirimi\Yongo\Repository\Workflow\WorkflowFunction;

    Util::checkUserIsLoggedInAndRedirect();

    $workflowDataId = $_GET['id'];
    $postFunctionId = $_GET['function_id'];
    $postFunctionSelected = WorkflowFunction::getById($postFunctionId);
    $workflowData = Workflow::getDataById($workflowDataId);
    $workflow = Workflow::getMetaDataById($workflowData['workflow_id']);

    $postFunctions = WorkflowFunction::getAll();

    if (isset($_POST['add_parameters'])) {

        if ($postFunctionId == WorkflowFunction::FUNCTION_SET_ISSUE_FIELD_VALUE) {
            $field_name = $_POST['issue_field'];
            $field_value = $_POST['field_value'];
            $value = 'field_name=' . $field_name . '###field_value=' . $field_value;

            WorkflowFunction::addPostFunction($workflowDataId, $postFunctionId, $value);

            $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));
            Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'ADD Yongo Workflow Post Function', $currentDate);
        }

        header('Location: /yongo/administration/workflow/transition-post-functions/' . $workflowDataId);
    }

    $menuSelectedCategory = 'issue';
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Post Function Data';
    require_once __DIR__ . '/../../../../../Resources/views/administration/workflow/transition/post_function/AddData.php';