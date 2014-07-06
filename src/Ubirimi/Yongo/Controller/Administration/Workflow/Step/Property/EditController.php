<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Workflow\Workflow;

    Util::checkUserIsLoggedInAndRedirect();

    $stepPropertyId = $_GET['id'];

    $stepProperty = Workflow::getStepPropertyById($stepPropertyId);
    $step = Workflow::getStepById($stepProperty['workflow_step_id']);
    $stepId = $step['id'];
    $workflowId = $step['workflow_id'];

    $workflowMetadata = Workflow::getMetaDataById($workflowId);
    $allProperties = Workflow::getSystemWorkflowProperties();
    $emptyValue = false;
    $duplicateKey = false;

    $value = $stepProperty['value'];

    if (isset($_POST['edit_property'])) {
        $keyId = Util::cleanRegularInputField($_POST['key']);
        $value = Util::cleanRegularInputField($_POST['value']);

        if (empty($value))
            $emptyValue = true;

        if (!$emptyValue) {

            $duplicateKey = Workflow::getStepKeyByStepIdAndKeyId($stepId, $keyId, $stepProperty['id']);

            if (!$duplicateKey) {

                $currentDate = Util::getServerCurrentDateTime();
                Workflow::updateStepPropertyById($stepPropertyId, $keyId, $value, $currentDate);

                Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'UPDATE Yongo Workflow Step Property', $currentDate);

                header('Location: /yongo/administration/workflow/view-step-properties/' . $stepId);
            }
        }
    }

    $menuSelectedCategory = 'issue';
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Workflow Step Property';

    require_once __DIR__ . '/../../../../../Resources/views/administration/workflow/step/property/Edit.php';