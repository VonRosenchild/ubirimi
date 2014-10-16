<?php

namespace Ubirimi\Yongo\Controller\Administration\Workflow\Step;

use Guzzle\Http\Message\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Settings;
use Ubirimi\Yongo\Repository\Workflow\Workflow;

class DeleteController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $stepId = $request->request->get('id');

        $this->getRepository('yongo.workflow.workflow')->deleteStepById($stepId);

        return new Response('');
    }
}