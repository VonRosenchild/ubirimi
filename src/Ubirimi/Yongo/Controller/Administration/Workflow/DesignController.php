<?php

namespace Ubirimi\Yongo\Controller\Administration\Workflow;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class DesignController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $workflowId = $request->get('id');

        $workflowMetadata = $this->getRepository('yongo.workflow.workflow')->getMetaDataById($workflowId);

        $workflowData = $this->getRepository('yongo.workflow.workflow')->getDataByWorkflowId($workflowId);

        if ($workflowMetadata['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $steps = $this->getRepository('yongo.workflow.workflow')->getSteps($workflowId, 1);
        $menuSelectedCategory = 'issue';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Workflow';

        return $this->render(__DIR__ . '/../../../Resources/views/administration/workflow/Design.php', get_defined_vars());
    }
}
