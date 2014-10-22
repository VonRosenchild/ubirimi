<?php

namespace Ubirimi\Yongo\Controller\Administration\Workflow\Transition;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class SaveDataController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $project_workflow_id = $request->request->get('project_workflow_id');
        $idFrom = $request->request->get('id_from');
        $idTo = $request->request->get('id_to');
        $name = $request->request->get('name');

        $this->getRepository('yongo.workflow.workflow')->createNewSingleDataRecord($project_workflow_id, $idFrom, $idTo, $name);

        return new Response('');
    }
}