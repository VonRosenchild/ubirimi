<?php
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Workflow\Workflow;
    use Ubirimi\Yongo\Repository\Workflow\WorkflowFunction;

    Util::checkUserIsLoggedInAndRedirect();

    $workflowDataId = $_GET['id'];
    $workflowData = Workflow::getDataById($workflowDataId);
    $workflow = Workflow::getMetaDataById($workflowData['workflow_id']);

    $postFunctions = WorkflowFunction::getAll();

    $errors = array('no_function_selected' => false);

    if (isset($_POST['add_new_post_function'])) {
        $functionId = isset($_POST['function']) ? $_POST['function'] : null;
        if ($functionId) {
            header('Location: /yongo/administration/workflow/transition-add-post-function-data/' . $workflowDataId . '?function_id=' . $functionId);
        } else {
            $errors['no_function_selected'] = true;
        }
    }
    $menuSelectedCategory = 'issue';

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Post Function';

    require_once __DIR__ . '/../../../../../Resources/views/administration/workflow/transition/post_function/Add.php';