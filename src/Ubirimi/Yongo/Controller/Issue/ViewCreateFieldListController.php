<?php

namespace Ubirimi\Yongo\Controller\Issue;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class ViewCreateFieldListController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $projectId = $request->request->get('project_id');

        $issueTypeId = $request->request->get('issue_type_id');
        $sysOperationId = $request->request->get('operation_id');
        $projectData = $this->getRepository('yongo.project.project')->getById($projectId);

        $workflowUsed = $this->getRepository('yongo.project.project')->getWorkflowUsedForType($projectId, $issueTypeId);

        return $this->render(__DIR__ . '/../../Resources/views/issue/ViewCreateFieldList.php', get_defined_vars());
    }
}
