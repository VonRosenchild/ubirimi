<?php

namespace Ubirimi\Yongo\Controller\Administration\Workflow\Step\Property;

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

        $clientId = $session->get('client/id');
        $loggedInUserId = $session->get('user/id');

        $stepId = $_GET['id'];
        $step = $this->getRepository('yongo.workflow.workflow')->getStepById($stepId);
        $workflowId = $step['workflow_id'];

        $workflowMetadata = $this->getRepository('yongo.workflow.workflow')->getMetaDataById($workflowId);

        if ($workflowMetadata['client_id'] != $clientId) {
            header('Location: /general-settings/bad-link-access-denied');
            die();
        }
        $allProperties = $this->getRepository('yongo.workflow.workflow')->getSystemWorkflowProperties();
        $emptyValue = false;
        $duplicateKey = false;

        if (isset($_POST['add_property'])) {
            $keyId = Util::cleanRegularInputField($_POST['key']);
            $value = Util::cleanRegularInputField($_POST['value']);

            if (empty($value))
                $emptyValue = true;

            if (!$emptyValue) {

                $duplicateKey = $this->getRepository('yongo.workflow.workflow')->getStepKeyByStepIdAndKeyId($stepId, $keyId);
                if (!$duplicateKey) {
                    $currentDate = Util::getServerCurrentDateTime();
                    $this->getRepository('yongo.workflow.workflow')->addStepProperty($stepId, $keyId, $value, $currentDate);

                    $this->getRepository('ubirimi.general.log')->add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'ADD Yongo Workflow Step Property' , $currentDate);

                    header('Location: /yongo/administration/workflow/view-step-properties/' . $stepId);
                }
            }
        }

        $menuSelectedCategory = 'issue';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Workflow Step Property';

        require_once __DIR__ . '/../../../../../Resources/views/administration/workflow/step/property/Add.php';
    }
}