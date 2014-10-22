<?php

namespace Ubirimi\Yongo\Controller\Administration\Workflow\Transition;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Workflow\Workflow;

use Ubirimi\SystemProduct;
use Ubirimi\Yongo\Repository\Screen\Screen;

class EditController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $workflowDataId = $request->get('id');
        $workflowData = $this->getRepository('yongo.workflow.workflow')->getDataById($workflowDataId);
        $workflowId = $workflowData['workflow_id'];
        $workflow = $this->getRepository('yongo.workflow.workflow')->getMetaDataById($workflowId);

        $screens = $this->getRepository('yongo.screen.screen')->getAll($session->get('client/id'));
        $steps = $this->getRepository('yongo.workflow.workflow')->getSteps($workflowId);

        if ($request->request->has('edit_transition')) {
            $name = Util::cleanRegularInputField($request->request->get('transition_name'));
            $description = Util::cleanRegularInputField($request->request->get('transition_description'));
            $step = $request->request->get('step');
            $screen = $request->request->get('screen');
            $this->getRepository('yongo.workflow.workflow')->updateDataById($workflowData['id'], $name, $description, $screen, $step);

            $currentDate = Util::getServerCurrentDateTime();
            $this->getRepository('ubirimi.general.log')->add(
                $session->get('client/id'),
                SystemProduct::SYS_PRODUCT_YONGO,
                $session->get('user/id'),
                'UPDATE Yongo Workflow Transition ' . $name,
                $currentDate
            );

            return new RedirectResponse('/yongo/administration/workflow/view-transition/' . $workflowDataId);
        }

        $menuSelectedCategory = 'issue';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Workflow Transition';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/workflow/transition/Edit.php', get_defined_vars());
    }
}
