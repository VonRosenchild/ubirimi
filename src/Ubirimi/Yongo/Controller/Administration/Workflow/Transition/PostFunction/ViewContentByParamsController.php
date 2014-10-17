<?php

namespace Ubirimi\Yongo\Controller\Administration\Workflow\Transition\PostFunction;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Workflow\Workflow;

class ViewContentByParamsController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $idFrom = $request->get('id_from');
        $idTo = $request->get('id_to');
        $workflowId = $request->get('workflow_id');

        $allPostFunctions = $this->getRepository('yongo.workflow.workflowFunction')->getAll();
        $workflowData = $this->getRepository('yongo.workflow.workflow')->getDataByStepIdFromAndStepIdTo($workflowId, $idFrom, $idTo);
        $workflowDataId = $workflowData['id'];

        $postFunctions = $this->getRepository('yongo.workflow.workflowFunction')->getByWorkflowDataId($workflowDataId);

        require_once __DIR__ . '/../../../../../Resources/views/administration/workflow/transition/post_function/ViewContentByParams.php';

    }
}