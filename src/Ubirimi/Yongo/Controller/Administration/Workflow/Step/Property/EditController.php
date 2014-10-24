<?php

namespace Ubirimi\Yongo\Controller\Administration\Workflow\Step\Property;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Workflow\Workflow;

class EditController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientId = $session->get('client/id');
        $loggedInUserId = $session->get('user/id');

        $stepPropertyId = $request->get('id');

        $stepProperty = $this->getRepository(Workflow::class)->getStepPropertyById($stepPropertyId);
        $step = $this->getRepository(Workflow::class)->getStepById($stepProperty['workflow_step_id']);
        $stepId = $step['id'];
        $workflowId = $step['workflow_id'];

        $workflowMetadata = $this->getRepository(Workflow::class)->getMetaDataById($workflowId);
        $allProperties = $this->getRepository(Workflow::class)->getSystemWorkflowProperties();
        $emptyValue = false;
        $duplicateKey = false;

        $value = $stepProperty['value'];

        if ($request->request->has('edit_property')) {
            $keyId = Util::cleanRegularInputField($request->request->get('key'));
            $value = Util::cleanRegularInputField($request->request->get('value'));

            if (empty($value))
                $emptyValue = true;

            if (!$emptyValue) {

                $duplicateKey = $this->getRepository(Workflow::class)->getStepKeyByStepIdAndKeyId($stepId, $keyId, $stepProperty['id']);

                if (!$duplicateKey) {

                    $currentDate = Util::getServerCurrentDateTime();
                    $this->getRepository(Workflow::class)->updateStepPropertyById($stepPropertyId, $keyId, $value, $currentDate);

                    $this->getRepository(UbirimiLog::class)->add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'UPDATE Yongo Workflow Step Property', $currentDate);

                    return new RedirectResponse('/yongo/administration/workflow/view-step-properties/' . $stepId);
                }
            }
        }

        $menuSelectedCategory = 'issue';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Workflow Step Property';

        return $this->render(__DIR__ . '/../../../../../Resources/views/administration/workflow/step/property/Edit.php', get_defined_vars());
    }
}