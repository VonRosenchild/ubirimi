<?php

use Ubirimi\Repository\Client;
use Ubirimi\Repository\Log;
use Ubirimi\SystemProduct;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Field\FieldConfigurationScheme;
use Ubirimi\Yongo\Repository\Issue\IssueTypeScheme;
use Ubirimi\Yongo\Repository\Issue\IssueTypeScreenScheme;
use Ubirimi\Yongo\Repository\Notification\NotificationScheme;
use Ubirimi\Yongo\Repository\Permission\PermissionScheme;
use Ubirimi\Yongo\Repository\Project\Project;
use Ubirimi\Yongo\Repository\Project\ProjectCategory;
use Ubirimi\Yongo\Repository\Workflow\WorkflowScheme;
use Ubirimi\Entity\Yongo\Project as ProjectEntity;
    use Ubirimi\Yongo\Controller\Administration\Service\ProjectService;

    Util::checkUserIsLoggedInAndRedirect();

    $leadUsers = Client::getUsers($session->get('client/id'));
    $forHelpDesk = isset($_GET['helpdesk']) ? true : false;

    $emptyName = false;
    $duplicateName = false;
    $emptyCode = false;
    $duplicateCode = false;

    $issueTypeScheme = IssueTypeScheme::getByClientId($clientId, 'project');
    $issueTypeScreenScheme = IssueTypeScreenScheme::getByClientId($clientId);
    $fieldConfigurationSchemes = FieldConfigurationScheme::getByClient($clientId);
    $workflowScheme = WorkflowScheme::getMetaDataByClientId($clientId);
    $permissionScheme = PermissionScheme::getByClientId($clientId);
    $notificationScheme = NotificationScheme::getByClientId($clientId);
    $projectCategories = ProjectCategory::getAll($clientId);

    if (isset($_POST['confirm_new_project'])) {

        $forHelpDesk = isset($_GET['helpdesk']) ? 1 : 0;
        $name = Util::cleanRegularInputField($_POST['name']);
        $code = strtoupper(Util::cleanRegularInputField($_POST['code']));
        $description = Util::cleanRegularInputField($_POST['description']);

        $issueTypeSchemeId = $_POST['issue_type_scheme'];
        $issueTypeScreenSchemeId = $_POST['issue_type_screen_scheme'];
        $issueTypeFieldConfigurationSchemeId = $_POST['field_configuration_scheme'];
        $workflowSchemeId = $_POST['workflow_scheme'];
        $permissionSchemeId = $_POST['permission_scheme'];
        $notificationSchemeId = $_POST['notification_scheme'];
        $leadId = Util::cleanRegularInputField($_POST['lead']);
        $projectCategoryId = Util::cleanRegularInputField($_POST['project_category']);

        if (-1 == $projectCategoryId) {
            $projectCategoryId = null;
        }

        if (empty($name))
            $emptyName = true;

        if (empty($code))
            $emptyCode = true;
        else {
            $projectExists = Project::getByCode(mb_strtolower($code), null, $clientId);
            if ($projectExists)
                $duplicateCode = true;
        }
        $projectExists = Project::getByName(mb_strtolower($name), null, $clientId);
        if ($projectExists)
            $duplicateName = true;

        if (!$emptyName && !$emptyCode && !$duplicateName && !$duplicateCode) {

            $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));

            $project = new ProjectEntity();
            $project->setClientId($clientId);
            $project->setName($name);
            $project->setCode($code);
            $project->setDescription($description);
            $project->setIssueTypeSchemeId($issueTypeSchemeId);
            $project->setIssueTypeScreenSchemeId($issueTypeScreenSchemeId);
            $project->setIssueTypeFieldConfigurationId($issueTypeFieldConfigurationSchemeId);
            $project->setWorkflowSchemeId($workflowSchemeId);
            $project->setPermissionSchemeId($permissionSchemeId);
            $project->setNotificationSchemeId($notificationSchemeId);
            $project->setLeadId($leadId);
            $project->setProjectCategoryId($projectCategoryId);
            $project->setServiceDeskEnabledFlag($forHelpDesk);
            $project->setDateCreated($currentDate);

            $projectId = ProjectService::add($project, $loggedInUserId);

            $session->set('selected_project_id', $projectId);

            Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'ADD Yongo Project ' . $name, $currentDate);

            if ($forHelpDesk) {
                header('Location: /helpdesk/all');
            } else {
                header('Location: /yongo/administration/projects');
            }
        }
    }

    $menuSelectedCategory = 'project';
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Project';

    require_once __DIR__ . '/../../../Resources/views/administration/project/Add.php';