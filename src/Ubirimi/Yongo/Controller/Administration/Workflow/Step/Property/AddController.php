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
    $allProperties = Workflow::getSystemWorkflowProperties();
    $emptyValue = false;
    $duplicateKey = false;

    if (isset($_POST['add_property'])) {
        $keyId = Util::cleanRegularInputField($_POST['key']);
        $value = Util::cleanRegularInputField($_POST['value']);

        if (empty($value))
            $emptyValue = true;

        if (!$emptyValue) {

            $duplicateKey = Workflow::getStepKeyByStepIdAndKeyId($stepId, $keyId);
            if (!$duplicateKey) {
                $currentDate = Util::getServerCurrentDateTime();
                Workflow::addStepProperty($stepId, $keyId, $value, $currentDate);

                Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'ADD Yongo Workflow Step Property' , $currentDate);

                header('Location: /yongo/administration/workflow/view-step-properties/' . $stepId);
            }
        }
    }

    $menuSelectedCategory = 'issue';
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Workflow Step Property';

    require_once __DIR__ . '/../../../../../Resources/views/administration/workflow/step/property/Add.php';