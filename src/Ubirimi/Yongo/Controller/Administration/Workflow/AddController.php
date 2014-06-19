<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueTypeScheme;
    use Ubirimi\Yongo\Repository\Workflow\Workflow;

    Util::checkUserIsLoggedInAndRedirect();

    $emptyName = false;
    $workflowExists = false;
    $workflowIssueTypeSchemes = IssueTypeScheme::getByClientId($clientId, 'workflow');

    if (isset($_POST['new_workflow'])) {
        $name = Util::cleanRegularInputField($_POST['name']);
        $description = Util::cleanRegularInputField($_POST['description']);

        if (empty($name))
            $emptyName = true;

        $duplicateWorkflow = Workflow::getByClientIdAndName($clientId, mb_strtolower($name));
        if ($duplicateWorkflow)
            $workflowExists = true;

        if (!$emptyName && !$workflowExists) {
            $workflowIssueTypeSchemeId = $_POST['workflow_issue_type_scheme'];

            $currentDate = $date = Util::getCurrentDateTime($session->get('client/settings/timezone'));

            $workflowId = Workflow::createNewMetaData($clientId, $workflowIssueTypeSchemeId, $name, $description, $currentDate);
            Workflow::createInitialData($clientId, $workflowId);

            Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'ADD Yongo Workflow ' . $name, $currentDate);

            header('Location: /yongo/administration/workflows');
        }
    }

    $menuSelectedCategory = 'issue';
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Workflow';
    require_once __DIR__ . '/../../../Resources/views/administration/workflow/Add.php';