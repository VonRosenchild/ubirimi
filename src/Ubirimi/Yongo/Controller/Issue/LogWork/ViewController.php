<?php

namespace Ubirimi\Yongo\Controller\Issue\LogWork;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\IssueWorkLog;
use Ubirimi\Repository\Client;
use Ubirimi\Yongo\Repository\Permission\Permission;
use Ubirimi\Yongo\Repository\Project\Project;

class ViewController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        if (Util::checkUserIsLoggedIn()) {

        } else {
            $httpHOST = Util::getHttpHost();
            $clientId = Client::getByBaseURL($httpHOST, 'array', 'id');
            $loggedInUserId = null;
        }

        $issueId = $request->request->get('issue_id');
        $projectId = $request->request->get('project_id');

        $workLogs = IssueWorkLog::getByIssueId($issueId);

        $hasEditOwnWorklogsPermission = Project::userHasPermission($projectId, Permission::PERM_EDIT_OWN_WORKLOGS, $session->get('user/id'));
        $hasEditAllWorklogsPermission = Project::userHasPermission($projectId, Permission::PERM_EDIT_ALL_WORKLOGS, $session->get('user/id'));

        $hasDeleteOwnWorklogsPermission = Project::userHasPermission($projectId, Permission::PERM_DELETE_OWN_WORKLOGS, $session->get('user/id'));
        $hasDeleteAllWorklogsPermission = Project::userHasPermission($projectId, Permission::PERM_DELETE_ALL_WORKLOGS, $session->get('user/id'));

        return $this->render(__DIR__ . '/../../../Resources/views/issue/log_work/View.php', get_defined_vars());
    }
}
