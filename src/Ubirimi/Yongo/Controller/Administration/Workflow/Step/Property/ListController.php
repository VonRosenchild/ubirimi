<?php

namespace Ubirimi\Yongo\Controller\Administration\Workflow\Step\Property;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Workflow\Workflow;

class ListController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $stepId = $request->get('id');
        $step = $this->getRepository(Workflow::class)->getStepById($stepId);
        $workflowId = $step['workflow_id'];

        $workflow = $this->getRepository(Workflow::class)->getMetaDataById($workflowId);

        $menuSelectedCategory = 'issue';
        $stepProperties = $this->getRepository(Workflow::class)->getStepProperties($stepId);

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Workflow Step Properties';

        return $this->render(__DIR__ . '/../../../../../Resources/views/administration/workflow/step/property/List.php', get_defined_vars());
    }
}