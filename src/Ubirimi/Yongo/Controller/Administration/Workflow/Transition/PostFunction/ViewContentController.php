<?php

namespace Ubirimi\Yongo\Controller\Administration\Workflow\Transition\PostFunction;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\IssueSettings;
use Ubirimi\Yongo\Repository\Workflow\WorkflowFunction;

class ViewContentController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientId = $session->get('client/id');
        $functionId = $request->request->get('function_id');

        $function = $this->getRepository(WorkflowFunction::class)->getById($functionId);
        $issueResolutions = $this->getRepository(IssueSettings::class)->getAllIssueSettings('resolution', $clientId);

        return $this->render(__DIR__ . '/../../../../../Resources/views/administration/workflow/transition/post_function/ViewContent.php', get_defined_vars());
    }
}

