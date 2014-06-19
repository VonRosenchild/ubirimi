<?php

    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Project\Project;
    use Ubirimi\Yongo\Repository\Workflow\WorkflowScheme;

    Util::checkUserIsLoggedInAndRedirect();

    $projectId = $_GET['id'];
    $project = Project::getById($projectId);
    if ($project['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    if (isset($_POST['associate'])) {

        $workflowSchemeId = $_POST['workflow_scheme'];

        header('Location: /yongo/administration/project/workflows/update-status/' . $projectId . '/' . $workflowSchemeId);
    }

    $workflowSchemes = WorkflowScheme::getMetaDataByClientId($clientId);
    $menuSelectedCategory = 'project';

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Select Project Workflow Scheme';

    require_once __DIR__ . '/../../../../Resources/views/administration/project/SelectWorkflowScheme.php';