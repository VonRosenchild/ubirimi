<?php

namespace Ubirimi\Yongo\Controller\Administration\Workflow\Transition;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class DeleteController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $workflowId = $request->request->get('workflow_id');
        $idFrom = $request->request->get('id_from');
        $idTo = $request->request->get('id_to');
        $this->getRepository('yongo.workflow.workflow')->deleteRecord($workflowId, $idFrom, $idTo);

        return new Response('');
    }
}
