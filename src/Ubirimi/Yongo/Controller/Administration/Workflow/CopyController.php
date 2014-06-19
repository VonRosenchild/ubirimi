<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Workflow\Workflow;

    Util::checkUserIsLoggedInAndRedirect();

    $workflowId = $_GET['id'];
    $workflow = Workflow::getMetaDataById($workflowId);

    if ($workflow['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    $emptyName = false;
    $duplicateName = false;

    if (isset($_POST['copy_workflow'])) {
        $name = Util::cleanRegularInputField($_POST['name']);
        $description = Util::cleanRegularInputField($_POST['description']);

        if (empty($name))
            $emptyName = true;
        $workflowAlreadyExisting = Workflow::getByClientIdAndName($clientId, $name);
        if ($workflowAlreadyExisting)
            $duplicateName = true;

        if (!$emptyName && !$workflowAlreadyExisting) {
            $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));
            Workflow::copy($clientId, $workflowId, $name, $description, $currentDate);

            Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'Copy Yongo Workflow ' . $workflow['name'], $currentDate);

            header('Location: /yongo/administration/workflows');
        }
    }

    $menuSelectedCategory = 'issue';

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Copy Workflow';

    require_once __DIR__ . '/../../../Resources/views/administration/workflow/Copy.php';