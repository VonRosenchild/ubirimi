<?php

namespace Ubirimi\Yongo\Controller\Administration\Workflow\Step;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Workflow\Workflow;

use Ubirimi\Yongo\Repository\Issue\Settings;

class EditController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $stepId = $request->get('id');
        $source = $request->get('source', 'step');

        $step = $this->getRepository('yongo.workflow.workflow')->getStepById($stepId);
        $workflowId = $step['workflow_id'];

        $workflow = $this->getRepository('yongo.workflow.workflow')->getMetaDataById($workflowId);
        $statuses = $this->getRepository('yongo.issue.settings')->getAllIssueSettings('status', $session->get('client/id'));

        $emptyName = false;

        if ($request->request->has('edit_step')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $StatusId = Util::cleanRegularInputField($request->request->get('status'));

            if (empty($name))
                $emptyName = true;

            if (!$emptyName) {
                $currentDate = Util::getServerCurrentDateTime();

                $this->getRepository('yongo.workflow.workflow')->updateStepById($stepId, $name, $StatusId, $currentDate);

                $this->getRepository('ubirimi.general.log')->add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_YONGO,
                    $session->get('user/id'),
                    'UPDATE Yongo Workflow Step ' . $step['name'],
                    $currentDate
                );

                if ($source == 'workflow_text') {
                    return new RedirectResponse('/yongo/administration/workflow/view-as-text/' . $workflowId);
                }

                return new RedirectResponse('/yongo/administration/workflow/view-step/' . $stepId);
            }
        }

        $menuSelectedCategory = 'issue';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Workflow Step';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/workflow/step/Edit.php', get_defined_vars());
    }
}
