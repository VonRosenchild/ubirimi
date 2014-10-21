<?php

namespace Ubirimi\Yongo\Controller\Issue;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Settings;
use Ubirimi\Yongo\Repository\Permission\Permission;
use Ubirimi\Yongo\Repository\Screen\Screen;

class TransitionDialogController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientId = $session->get('client/id');

        $workflowId = $request->get('id');
        $stepIdFrom = $request->get('step_id_from');
        $stepIdTo = $request->get('step_id_to');

        $projectId = $request->get('project_id');
        $issueId = $request->get('issue_id');
        $assignableUsers = $this->getRepository('yongo.project.project')->getUsersWithPermission($projectId, Permission::PERM_ASSIGNABLE_USER);
        $projectData = $this->getRepository('yongo.project.project')->getById($projectId);
        $issue = $this->getRepository('yongo.issue.issue')->getByIdSimple($issueId);
        $workflowData = $this->getRepository('yongo.workflow.workflow')->getDataByStepIdFromAndStepIdTo($workflowId, $stepIdFrom, $stepIdTo);
        $screenId = $workflowData['screen_id'];

        $allUsers = $this->getRepository('ubirimi.user.user')->getByClientId($session->get('client/id'));
        $screenData = Screen::getDataById($screenId);
        $screenMetadata = Screen::getMetaDataById($screenId);
        $resolutions = $this->getRepository('yongo.issue.settings')->getAllIssueSettings('resolution', $clientId);
        $projectComponents = $this->getRepository('yongo.project.project')->getComponents($projectId);
        $projectVersions = $this->getRepository('yongo.project.project')->getVersions($projectId);
        $htmlOutput = '';
        $htmlOutput .= '<table class="modal-table">';

        $reporterUsers = $this->getRepository('yongo.project.project')->getUsersWithPermission($projectId, Permission::PERM_CREATE_ISSUE);
        $fieldCodeNULL = null;

        $fieldData = $this->getRepository('yongo.project.project')->getFieldInformation($projectData['issue_type_field_configuration_id'], $issue['type_id'], 'array');

        return $this->render(__DIR__ . '/../../Resources/views/issue/TransitionDialog.php', get_defined_vars());

    }
}