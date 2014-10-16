<?php

namespace Ubirimi\Yongo\Controller\Administration\Workflow\Transition\PostFunction;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Workflow\Workflow;

class ViewContentController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientId = $session->get('client/id');
        $functionId = $request->request->get('function_id');

        $function = WorkflowFunction::getById($functionId);
        $issueResolutions = Settings::getAllIssueSettings('resolution', $clientId);

        require_once __DIR__ . '/../../../../../Resources/views/administration/workflow/transition/post_function/ViewContent.php';

    }
}

