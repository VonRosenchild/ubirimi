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

class AddController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientId = $session->get('client/id');
        $loggedInUserId = $session->get('user/id');

        $stepId = $request->get('id');
        $step = $this->getRepository(Workflow::class)->getStepById($stepId);
        $workflowId = $step['workflow_id'];

        $workflowMetadata = $this->getRepository(Workflow::class)->getMetaDataById($workflowId);

        if ($workflowMetadata['client_id'] != $clientId) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }
        $allProperties = $this->getRepository(Workflow::class)->getSystemWorkflowProperties();
        $emptyValue = false;
        $duplicateKey = false;

        if ($request->request->has('add_property')) {
            $keyId = Util::cleanRegularInputField($request->request->get('key'));
            $value = Util::cleanRegularInputField($request->request->get('value'));

            if (empty($value))
                $emptyValue = true;

            if (!$emptyValue) {

                $duplicateKey = $this->getRepository(Workflow::class)->getStepKeyByStepIdAndKeyId($stepId, $keyId);
                if (!$duplicateKey) {
                    $currentDate = Util::getServerCurrentDateTime();
                    $this->getRepository(Workflow::class)->addStepProperty($stepId, $keyId, $value, $currentDate);

                    $this->getRepository(UbirimiLog::class)->add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'ADD Yongo Workflow Step Property' , $currentDate);

                    return new RedirectResponse('/yongo/administration/workflow/view-step-properties/' . $stepId);
                }
            }
        }

        $menuSelectedCategory = 'issue';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Workflow Step Property';

        return $this->render(__DIR__ . '/../../../../../Resources/views/administration/workflow/step/property/Add.php', get_defined_vars());
    }
}