<?php

namespace Ubirimi\Yongo\Controller\Administration\Workflow\Transition;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Workflow\Workflow;

class ViewBrowserController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $workflowDataId = $request->get('id');

        $workflowData = Workflow::getDataById($workflowDataId);
        $workflow = Workflow::getMetaDataById($workflowData['workflow_id']);

        if ($workflow['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $menuSelectedCategory = 'issue';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Workflow Transition Browser';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/workflow/transition/ViewBrowser.php', get_defined_vars());
    }
}
