<?php

namespace Ubirimi\Yongo\Controller\Administration\Workflow;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class ViewAsTextController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $workflowId = $request->get('id');

        $workflowMetadata = $this->getRepository('yongo.workflow.workflow')->getMetaDataById($workflowId);

        if ($workflowMetadata['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $workflowSteps = $this->getRepository('yongo.workflow.workflow')->getSteps($workflowId);
        $menuSelectedCategory = 'issue';

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Workflow / Text View';

        return $this->render(__DIR__ . '/../../../Resources/views/administration/workflow/ViewAsText.php', get_defined_vars());
    }
}
