<?php

namespace Ubirimi\Yongo\Controller\Administration\Workflow\Step;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Settings;
use Ubirimi\Yongo\Repository\Workflow\Workflow;

class AddController extends UbirimiController
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
        $statuses = $this->getRepository('yongo.issue.settings')->getAllIssueSettings('status', $session->get('client/id'));
        $linkedStatuses = $this->getRepository('yongo.workflow.workflow')->getLinkedStatuses($workflowId, 'array', 'linked_issue_status_id');

        $addStepPossible = true;
        if (count($linkedStatuses) == $statuses->num_rows) {
            $addStepPossible = false;
        }

        $emptyName = false;
        $duplicateName = false;

        if ($request->request->has('add_step')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));

            if (empty($name)) {
                $emptyName = true;
            }

            $duplicateStep = $this->getRepository('yongo.workflow.workflow')->getStepByWorkflowIdAndName($workflowId, $name);
            if ($duplicateStep) {
                $duplicateName = true;
            }

            if (!$emptyName && !$duplicateName) {
                $currentDate = $date = Util::getServerCurrentDateTime();
                $StatusId = $request->request->get('linked_status');

                $this->getRepository('yongo.workflow.workflow')->addStep($workflowId, $name, $StatusId, 0, $currentDate);

                $this->getRepository('ubirimi.general.log')->add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_YONGO,
                    $session->get('user/id'),
                    'ADD Yongo Workflow Step ' . $name,
                    $currentDate
                );

                return new RedirectResponse('/yongo/administration/workflow/view-as-text/' . $workflowId);
            }
        }

        $menuSelectedCategory = 'issue';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Workflow Step';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/workflow/step/Add.php', get_defined_vars());
    }
}
