<?php

namespace Ubirimi\Yongo\Controller\Administration\Workflow\Step\Property;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class EditController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientId = $session->get('client/id');
        $loggedInUserId = $session->get('user/id');

        $stepPropertyId = $request->get('id');

        $stepProperty = $this->getRepository('yongo.workflow.workflow')->getStepPropertyById($stepPropertyId);
        $step = $this->getRepository('yongo.workflow.workflow')->getStepById($stepProperty['workflow_step_id']);
        $stepId = $step['id'];
        $workflowId = $step['workflow_id'];

        $workflowMetadata = $this->getRepository('yongo.workflow.workflow')->getMetaDataById($workflowId);
        $allProperties = $this->getRepository('yongo.workflow.workflow')->getSystemWorkflowProperties();
        $emptyValue = false;
        $duplicateKey = false;

        $value = $stepProperty['value'];

        if ($request->request->has('edit_property')) {
            $keyId = Util::cleanRegularInputField($request->request->get('key'));
            $value = Util::cleanRegularInputField($request->request->get('value'));

            if (empty($value))
                $emptyValue = true;

            if (!$emptyValue) {

                $duplicateKey = $this->getRepository('yongo.workflow.workflow')->getStepKeyByStepIdAndKeyId($stepId, $keyId, $stepProperty['id']);

                if (!$duplicateKey) {

                    $currentDate = Util::getServerCurrentDateTime();
                    $this->getRepository('yongo.workflow.workflow')->updateStepPropertyById($stepPropertyId, $keyId, $value, $currentDate);

                    $this->getRepository('ubirimi.general.log')->add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'UPDATE Yongo Workflow Step Property', $currentDate);

                    return new RedirectResponse('/yongo/administration/workflow/view-step-properties/' . $stepId);
                }
            }
        }

        $menuSelectedCategory = 'issue';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Workflow Step Property';

        require_once __DIR__ . '/../../../../../Resources/views/administration/workflow/step/property/Edit.php';
    }
}