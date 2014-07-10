<?php

namespace Ubirimi\Yongo\Controller\Administration\Workflow\Scheme;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Repository\Log;
use Ubirimi\SystemProduct;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Workflow\WorkflowScheme;
use Ubirimi\Yongo\Repository\Workflow\Workflow;

class ConfigureController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $Id = $request->get('id');
        $emptyName = false;
        $workflowScheme = WorkflowScheme::getMetaDataById($Id);

        if ($workflowScheme['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $allWorkflows = Workflow::getAllByClientId($session->get('client/id'));

        $schemeWorkflows = WorkflowScheme::getDataById($Id);

        $name = $workflowScheme['name'];
        $description = $workflowScheme['description'];

        if ($request->request->has('edit_workflow_scheme')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));
            $currentDate = Util::getServerCurrentDateTime();

            if (empty($name))
                $emptyName = true;

            if (!$emptyName) {
                WorkflowScheme::updateMetaDataById($Id, $name, $description);
                WorkflowScheme::deleteDataByWorkflowSchemeId($Id);
                foreach ($request->request as $key => $value) {
                    if (substr($key, 0, 9) == 'workflow_') {
                        $workflowId = str_replace('workflow_', '', $key);
                        WorkflowScheme::addData($Id, $workflowId, $currentDate);
                    }
                }

                Log::add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_YONGO,
                    $session->get('user/id'),
                    'UPDATE Yongo Workflow Scheme ' . $name,
                    $currentDate
                );

                return new RedirectResponse('/yongo/administration/workflows/schemes');
            }
        }

        $menuSelectedCategory = 'issue';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Workflow Scheme';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/workflow/scheme/Configure.php', get_defined_vars());
    }
}
