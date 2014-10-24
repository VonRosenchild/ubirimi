<?php

namespace Ubirimi\Yongo\Controller\Issue;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Permission\Permission;
use Ubirimi\Yongo\Repository\Project\YongoProject;

class AssignDialogController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $issueId = $request->get('issue_id');
        $projectId = $request->get('project_id');

        $assignableUsers = $this->getRepository(YongoProject::class)->getUsersWithPermission($projectId, Permission::PERM_ASSIGNABLE_USER);
        $allowUnassignedIssuesFlag = $this->getRepository(UbirimiClient::class)->getYongoSetting($session->get('client/id'), 'allow_unassigned_issues_flag');

        return $this->render(__DIR__ . '/../../Resources/views/issue/AssignDialog.php', get_defined_vars());
    }
}
