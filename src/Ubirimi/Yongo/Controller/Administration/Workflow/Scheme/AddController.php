<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Workflow\WorkflowScheme;

    Util::checkUserIsLoggedInAndRedirect();

    $emptyName = false;

    if (isset($_POST['new_workflow_scheme'])) {
        $name = Util::cleanRegularInputField($_POST['name']);
        $description = Util::cleanRegularInputField($_POST['description']);

        if (empty($name))
            $emptyName = true;

        if (!$emptyName) {
            $issueTypeScheme = new WorkflowScheme($clientId, $name, $description);
            $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));
            $issueTypeScheme->save($currentDate);

            Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'ADD Yongo Workflow Scheme ' . $name, $currentDate);

            header('Location: /yongo/administration/workflows/schemes');
        }
    }

    $menuSelectedCategory = 'issue';
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Workflow Scheme';

    require_once __DIR__ . '/../../../../Resources/views/administration/workflow/scheme/Add.php';