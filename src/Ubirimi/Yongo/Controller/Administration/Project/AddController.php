<?php

namespace Ubirimi\Yongo\Controller\Administration\Project;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Project\Project;
use Ubirimi\Repository\Client;
use Ubirimi\Repository\Log;
use Ubirimi\Yongo\Repository\Field\ConfigurationScheme;
use Ubirimi\Yongo\Repository\Issue\TypeScheme;
use Ubirimi\Yongo\Repository\Issue\TypeScreenScheme;
use Ubirimi\Yongo\Repository\Notification\Scheme;
use Ubirimi\Yongo\Repository\Permission\Scheme;
use Ubirimi\Yongo\Repository\Project\Category;
use Ubirimi\Yongo\Repository\Workflow\Scheme;
use Ubirimi\Entity\Yongo\Project as ProjectEntity;

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

        $issueTypeScheme = TypeScheme::getByClientId($session->get('client/id'), 'project');
        $issueTypeScreenScheme = TypeScreenScheme::getByClientId($session->get('client/id'));
        $fieldConfigurationSchemes = ConfigurationScheme::getByClient($session->get('client/id'));
        $workflowScheme = Scheme::getMetaDataByClientId($session->get('client/id'));
        $permissionScheme = Scheme::getByClientId($session->get('client/id'));
        $notificationScheme = Scheme::getByClientId($session->get('client/id'));
        $projectCategories = Category::getAll($session->get('client/id'));

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

            if (empty($name)) {
                $emptyName = true;
            }

            if (empty($code)) {
                $emptyCode = true;
            } else {
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
                $project->setHelpDeskDeskEnabledFlag($forHelpDesk);
                $project->setDateCreated($currentDate);

                $projectId = UbirimiContainer::get()['project']->add($project, $session->get('user/id'));

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
