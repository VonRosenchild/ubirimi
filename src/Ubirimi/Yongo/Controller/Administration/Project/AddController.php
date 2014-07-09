<?php

namespace Ubirimi\Yongo\Controller\Administration\Project;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Project\Project;
use Ubirimi\Repository\Client;
use Ubirimi\Repository\Log;
use Ubirimi\Yongo\Repository\Field\FieldConfigurationScheme;
use Ubirimi\Yongo\Repository\Issue\IssueTypeScheme;
use Ubirimi\Yongo\Repository\Issue\IssueTypeScreenScheme;
use Ubirimi\Yongo\Repository\Notification\NotificationScheme;
use Ubirimi\Yongo\Repository\Permission\PermissionScheme;
use Ubirimi\Yongo\Repository\Project\ProjectCategory;
use Ubirimi\Yongo\Repository\Workflow\WorkflowScheme;
use Ubirimi\Entity\Yongo\Project as ProjectEntity;
use Ubirimi\Yongo\Controller\Administration\Service\ProjectService;

class AddController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $leadUsers = Client::getUsers($session->get('client/id'));
        $forHelpDesk = $request->query->has('helpdesk') ? true : false;

        $emptyName = false;
        $duplicateName = false;
        $emptyCode = false;
        $duplicateCode = false;

        $issueTypeScheme = IssueTypeScheme::getByClientId($session->get('client/id'), 'project');
        $issueTypeScreenScheme = IssueTypeScreenScheme::getByClientId($session->get('client/id'));
        $fieldConfigurationSchemes = FieldConfigurationScheme::getByClient($session->get('client/id'));
        $workflowScheme = WorkflowScheme::getMetaDataByClientId($session->get('client/id'));
        $permissionScheme = PermissionScheme::getByClientId($session->get('client/id'));
        $notificationScheme = NotificationScheme::getByClientId($session->get('client/id'));
        $projectCategories = ProjectCategory::getAll($session->get('client/id'));

        if ($request->request->has('confirm_new_project')) {

            $forHelpDesk = $request->query->has('helpdesk') ? 1 : 0;
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $code = strtoupper(Util::cleanRegularInputField($request->request->get('code')));
            $description = Util::cleanRegularInputField($request->request->get('description'));

            $issueTypeSchemeId = $request->request->get('issue_type_scheme');
            $issueTypeScreenSchemeId = $request->request->get('issue_type_screen_scheme');
            $issueTypeFieldConfigurationSchemeId = $request->request->get('field_configuration_scheme');
            $workflowSchemeId = $request->request->get('workflow_scheme');
            $permissionSchemeId = $request->request->get('permission_scheme');
            $notificationSchemeId = $request->request->get('notification_scheme');
            $leadId = Util::cleanRegularInputField($request->request->get('lead'));
            $projectCategoryId = Util::cleanRegularInputField($request->request->get('project_category'));

            if (-1 == $projectCategoryId) {
                $projectCategoryId = null;
            }

            if (empty($name))
                $emptyName = true;

            if (empty($code))
                $emptyCode = true;
            else {
                $projectExists = Project::getByCode(mb_strtolower($code), null, $session->get('client/id'));
                if ($projectExists)
                    $duplicateCode = true;
            }
            $projectExists = Project::getByName(mb_strtolower($name), null, $session->get('client/id'));
            if ($projectExists)
                $duplicateName = true;

            if (!$emptyName && !$emptyCode && !$duplicateName && !$duplicateCode) {

                $currentDate = Util::getServerCurrentDateTime();

                $project = new ProjectEntity();
                $project->setClientId($session->get('client/id'));
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

                $projectId = ProjectService::add($project, $session->get('user/id'));

                $session->set('selected_project_id', $projectId);

                Log::add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_YONGO,
                    $session->get('user/id'),
                    'ADD Yongo Project ' . $name,
                    $currentDate
                );

                if ($forHelpDesk) {
                    return new RedirectResponse('/helpdesk/all');
                } else {
                    return new RedirectResponse('/yongo/administration/projects');
                }
            }
        }

        $menuSelectedCategory = 'project';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Project';

        return $this->render(__DIR__ . '/../../../Resources/views/administration/project/Add.php', get_defined_vars());
    }
}
