<?php

namespace Ubirimi\Yongo\Controller\Administration\Workflow;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Workflow\Workflow;
use Ubirimi\Repository\Log;
use Ubirimi\Yongo\Repository\Issue\TypeScheme;

class EditMetadataController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $workflowId = $request->get('id');

        $workflow = $this->getRepository('yongo.workflow.workflow')->getMetaDataById($workflowId);
        $workflowIssueTypeSchemes = TypeScheme::getByClientId($session->get('client/id'), 'workflow');

        if ($workflow['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $emptyName = false;

        if ($request->request->has('edit_workflow')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));
            $workflowIssueTypeSchemeId = $request->request->get('workflow_issue_type_scheme');

            if (empty($name))
                $emptyName = true;

            if (!$emptyName) {
                $currentDate = Util::getServerCurrentDateTime();

                $this->getRepository('yongo.workflow.workflow')->updateMetaDataById($workflowId, $name, $description, $workflowIssueTypeSchemeId, $currentDate);

                $this->getRepository('ubirimi.general.log')->add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_YONGO,
                    $session->get('user/id'),
                    'UPDATE Yongo Workflow ' . $name,
                    $currentDate
                );

                return new RedirectResponse('/yongo/administration/workflows');
            }
        }

        $menuSelectedCategory = 'issue';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Workflow';

        return $this->render(__DIR__ . '/../../../Resources/views/administration/workflow/EditMetadata.php', get_defined_vars());
    }
}
