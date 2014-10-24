<?php

namespace Ubirimi\Yongo\Controller\Administration\Workflow;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\IssueTypeScheme;
use Ubirimi\Yongo\Repository\Workflow\Workflow;

class AddController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $emptyName = false;
        $workflowExists = false;
        $workflowIssueTypeSchemes = IssueTypeScheme::getByClientId($session->get('client/id'), 'workflow');

        if ($request->request->has('new_workflow')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));

            if (empty($name))
                $emptyName = true;

            $duplicateWorkflow = $this->getRepository(Workflow::class)->getByClientIdAndName($session->get('client/id'), mb_strtolower($name));
            if ($duplicateWorkflow)
                $workflowExists = true;

            if (!$emptyName && !$workflowExists) {
                $workflowIssueTypeSchemeId = $request->request->get('workflow_issue_type_scheme');

                $currentDate = $date = Util::getServerCurrentDateTime();

                $workflowId = $this->getRepository(Workflow::class)->createNewMetaData(
                    $session->get('client/id'),
                    $workflowIssueTypeSchemeId,
                    $name,
                    $description,
                    $currentDate
                );

                $this->getRepository(Workflow::class)->createInitialData($session->get('client/id'), $workflowId);

                $this->getRepository(UbirimiLog::class)->add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_YONGO,
                    $session->get('user/id'),
                    'ADD Yongo Workflow ' . $name,
                    $currentDate
                );

                return new RedirectResponse('/yongo/administration/workflows');
            }
        }

        $menuSelectedCategory = 'issue';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Workflow';

        return $this->render(__DIR__ . '/../../../Resources/views/administration/workflow/Add.php', get_defined_vars());
    }
}
