<?php

namespace Ubirimi\Yongo\Controller\Administration\Workflow\Transition;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class UpdateController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $transition_name = $request->request->get('transition_name');
        $workflowId = $request->request->get('workflow_id');
        $screenId = isset($_POST['screen_id']) ? $_POST['screen_id'] : null;
        $idFrom = $request->request->get('id_from');
        $idTo = $request->request->get('id_to');

        $this->getRepository('yongo.workflow.workflow')->updateTransitionData($workflowId, $transition_name, $screenId, $idFrom, $idTo);

        return new Response('');
    }
}