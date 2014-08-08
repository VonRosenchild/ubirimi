<?php

namespace Ubirimi\Yongo\Controller\Administration\Workflow\Step\Transition;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Workflow\Workflow;
use Ubirimi\Repository\Log;

class DeleteController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $stepId = $request->get('id');

        $step = Workflow::getStepById($stepId);
        $workflowId = $step['workflow_id'];

        $workflowMetadata = Workflow::getMetaDataById($workflowId);

        if ($workflowMetadata['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $transitions = Workflow::getOutgoingTransitionsForStep($workflowId, $stepId);

        if ($request->request->has('delete_transitions')) {
            $transitionsPosted = $request->request->get('transitions');

            Workflow::deleteTransitions($workflowId, $transitionsPosted);

            $currentDate = Util::getServerCurrentDateTime();

            Log::add(
                $session->get('client/id'),
                SystemProduct::SYS_PRODUCT_YONGO,
                $session->get('user/id'),
                'DELETE Yongo Workflow Transition',
                $currentDate
            );

            return new RedirectResponse('/yongo/administration/workflow/view-as-text/' . $workflowId);
        }

        $menuSelectedCategory = 'issue';

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Delete Transitions';

        return $this->render(__DIR__ . '/../../../../../Resources/views/administration/workflow/step/transition/Delete.php', get_defined_vars());
    }
}
