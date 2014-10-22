<?php

namespace Ubirimi\Yongo\Controller\Issue;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Settings;
use Ubirimi\Yongo\Repository\Issue\SystemOperation;
use Ubirimi\Yongo\Repository\Permission\Permission;


class AddSubtaskDialogController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $issueId = $request->get('issue_id');
        $projectId = $request->get('project_id');

        $projectData = $this->getRepository('yongo.project.project')->getById($projectId);
        $projectId = $projectData['id'];

        $issue_priorities = $this->getRepository('yongo.issue.settings')->getAllIssueSettings('priority', $session->get('client/id'));
        $issue_types = $this->getRepository('yongo.project.project')->getSubTasksIssueTypes($projectId);

        $firstIssueType = $issue_types->fetch_array(MYSQLI_ASSOC);
        $issueTypeId = $firstIssueType['id'];
        $issue_types->data_seek(0);

        $screenData = $this->getRepository('yongo.project.project')->getScreenData($projectData, $issueTypeId, SystemOperation::OPERATION_CREATE);
        $projectComponents = $this->getRepository('yongo.project.project')->getComponents($projectId);
        $projectVersions = $this->getRepository('yongo.project.project')->getVersions($projectId);

        $assignableUsers = $this->getRepository('yongo.project.project')->getUsersWithPermission($projectId, Permission::PERM_ASSIGNABLE_USER);
        $reporterUsers = $this->getRepository('yongo.project.project')->getUsersWithPermission($projectId, Permission::PERM_CREATE_ISSUE);

        $userHasModifyReporterPermission = $this->getRepository('yongo.project.project')->userHasPermission($projectId, Permission::PERM_MODIFY_REPORTER, $session->get('user/id'));
        $userHasAssignIssuePermission = $this->getRepository('yongo.project.project')->userHasPermission($projectId, Permission::PERM_ASSIGN_ISSUE, $session->get('user/id'));

        $typeId = null;

        return $this->render(__DIR__ . '/../../Resources/views/issue/render_create_subtask.php', get_defined_vars());
    }
}
