<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueTypeScheme;
    use Ubirimi\Yongo\Repository\Workflow\Workflow;

    Util::checkUserIsLoggedInAndRedirect();

    $workflowId = $_GET['id'];

    $workflow = Workflow::getMetaDataById($workflowId);
    $workflowIssueTypeSchemes = IssueTypeScheme::getByClientId($clientId, 'workflow');

    if ($workflow['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    $emptyName = false;

    if (isset($_POST['edit_workflow'])) {
        $name = Util::cleanRegularInputField($_POST['name']);
        $description = Util::cleanRegularInputField($_POST['description']);
        $workflowIssueTypeSchemeId = $_POST['workflow_issue_type_scheme'];

        if (empty($name))
            $emptyName = true;

        if (!$emptyName) {
            $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));

            Workflow::updateMetaDataById($workflowId, $name, $description, $workflowIssueTypeSchemeId, $currentDate);

            Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'UPDATE Yongo Workflow ' . $name, $currentDate);

            header('Location: /yongo/administration/workflows');
        }
    }
    $menuSelectedCategory = 'issue';
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Workflow';

    require_once __DIR__ . '/../../../Resources/views/administration/workflow/EditMetadata.php';