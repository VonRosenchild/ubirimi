<?php
    use Ubirimi\Repository\Client;
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueTypeScheme;
    use Ubirimi\Yongo\Repository\Issue\IssueTypeScreenScheme;
    use Ubirimi\Yongo\Repository\Project\Project;
    use Ubirimi\Yongo\Repository\Project\ProjectCategory;
    use Ubirimi\Yongo\Repository\Workflow\WorkflowScheme;

    Util::checkUserIsLoggedInAndRedirect();

    $projectId = $_GET['id'];
    $leadUsers = Client::getUsers($session->get('client/id'));

    // todo: leadul sa fie adaugat in lista de useri pentru acest proiect
    $project = Project::getById($projectId);

    if ($project['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    $issueTypeScheme = IssueTypeScheme::getByClientId($clientId, 'project');
    $issueTypeScreenScheme = IssueTypeScreenScheme::getByClientId($clientId);
    $workflowScheme = WorkflowScheme::getMetaDataByClientId($clientId);
    $projectCategories = ProjectCategory::getAll($clientId);

    $emptyName = false;
    $duplicate_name = false;
    $duplicate_code = false;
    $empty_code = false;
    
    if (isset($_POST['confirm_edit_project'])) {
        $name = Util::cleanRegularInputField($_POST['name']);
        $code = Util::cleanRegularInputField($_POST['code']);
        $description = Util::cleanRegularInputField($_POST['description']);

        $issueTypeSchemeId = $_POST['issue_type_scheme'];
        $workflowSchemeId = $_POST['workflow_scheme'];
        $projectCategoryId = $_POST['project_category'];
        $enableForHelpdeskFlag = $_POST['enable_for_helpdesk'];

        if (-1 == $projectCategoryId) {
            $projectCategoryId = null;
        }

        $lead_id = Util::cleanRegularInputField($_POST['lead']);

        if (empty($name)) {
            $emptyName = true;
        } else {
            $duplicateProjectByName = Project::getByName(mb_strtolower($name), $projectId, $clientId);
            if ($duplicateProjectByName) {
                $duplicate_name = true;
            }
        }

        if (empty($code))
            $empty_code = true;
        else {
            $project_exists = Project::getByCode(mb_strtolower($code), $projectId, $clientId);
            if ($project_exists)
                $duplicate_code = true;
        }

        if (!$emptyName && !$empty_code && !$duplicate_name && !$duplicate_code) {
            $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));
            Project::updateById($projectId, $lead_id, $name, $code, $description, $issueTypeSchemeId, $workflowSchemeId, $projectCategoryId, $enableForHelpdeskFlag, $currentDate);

            Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'UPDATE Yongo Project ' . $name, $currentDate);
            header('Location: /yongo/administration/projects');
        }
    }

    $menuSelectedCategory = 'project';

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Project';

    require_once __DIR__ . '/../../../Resources/views/administration/project/Edit.php';