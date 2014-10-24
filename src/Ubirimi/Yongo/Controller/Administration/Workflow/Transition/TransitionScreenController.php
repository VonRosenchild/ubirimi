<?php

namespace Ubirimi\Yongo\Controller\Administration\Workflow\Transition;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Screen\Screen;
use Ubirimi\Yongo\Repository\Workflow\Workflow;

class TransitionScreenController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientId = $session->get('client/id');

        $stepIdFrom = $request->get('id_from');
        $stepIdTo = $request->get('id_to');
        $workflowId = $request->get('workflow_id');

        $workflowMetadata = $this->getRepository(Workflow::class)->getMetaDataById($workflowId);

        $workflowData = $this->getRepository(Workflow::class)->getDataByStepIdFromAndStepIdTo($workflowId, $stepIdFrom, $stepIdTo);
        $transitionName = $workflowData['transition_name'];
        $screens = $this->getRepository(Screen::class)->getAll($clientId);
        $initialStep = $this->getRepository(Workflow::class)->getInitialStep($workflowId);

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/workflow/transition/TransitionScreen.php', get_defined_vars());
    }
}