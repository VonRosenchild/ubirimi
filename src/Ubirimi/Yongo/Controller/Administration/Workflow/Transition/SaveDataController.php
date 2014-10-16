<?php

namespace Ubirimi\Yongo\Controller\Administration\Workflow\Transition;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Workflow\Workflow;
use Ubirimi\SystemProduct;
use Ubirimi\Yongo\Repository\Screen\Screen;

class SaveDataController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $project_workflow_id = $_POST['project_workflow_id'];
        $idFrom = $_POST['id_from'];
        $idTo = $_POST['id_to'];
        $name = $_POST['name'];

        $this->getRepository('yongo.workflow.workflow')->createNewSingleDataRecord($project_workflow_id, $idFrom, $idTo, $name);

        return new Response('');
    }
}