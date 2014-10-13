<?php

namespace Ubirimi\Yongo\Controller\Administration\Workflow;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Workflow\Workflow;
use Ubirimi\Repository\Log;

class CopyController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $workflowId = $request->get('id');
        $workflow = $this->getRepository('yongo.workflow.workflow')->getMetaDataById($workflowId);

        if ($workflow['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $emptyName = false;
        $duplicateName = false;

        if ($request->request->has('copy_workflow')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));

            if (empty($name)) {
                $emptyName = true;
            }

            $workflowAlreadyExisting = $this->getRepository('yongo.workflow.workflow')->getByClientIdAndName($session->get('client/id'), $name);
            if ($workflowAlreadyExisting) {
                $duplicateName = true;
            }

            if (!$emptyName && !$workflowAlreadyExisting) {
                $currentDate = Util::getServerCurrentDateTime();
                $this->getRepository('yongo.workflow.workflow')->copy($session->get('client/id'), $workflowId, $name, $description, $currentDate);

                $this->getRepository('ubirimi.general.log')->add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_YONGO,
                    $session->get('user/id'),
                    'Copy Yongo Workflow ' . $workflow['name'],
                    $currentDate
                );

                return new RedirectResponse('/yongo/administration/workflows');
            }
        }

        $menuSelectedCategory = 'issue';

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Copy Workflow';

        return $this->render(__DIR__ . '/../../../Resources/views/administration/workflow/Copy.php', get_defined_vars());
    }
}
