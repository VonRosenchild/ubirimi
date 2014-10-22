<?php

namespace Ubirimi\Yongo\Controller\Administration\Workflow\Transition\PostFunction;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class ListController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientId = $session->get('client/id');

        $workflowDataId = $request->get('id');
        $workflowData = $this->getRepository('yongo.workflow.workflow')->getDataById($workflowDataId);
        $workflow = $this->getRepository('yongo.workflow.workflow')->getMetaDataById($workflowData['workflow_id']);

        if ($workflow['client_id'] != $clientId) {
            header('Location: /general-settings/bad-link-access-denied');
            die();
        }

        $postFunctions = $this->getRepository('yongo.workflow.workflowFunction')->getByWorkflowDataId($workflowDataId);

        $menuSelectedCategory = 'issue';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Workflow Transition Post Function';
        require_once __DIR__ . '/../../../../../Resources/views/administration/workflow/transition/post_function/View.php';
    }
}