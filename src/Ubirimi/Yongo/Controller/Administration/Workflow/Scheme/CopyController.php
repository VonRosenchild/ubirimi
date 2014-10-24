<?php

namespace Ubirimi\Yongo\Controller\Administration\Workflow\Scheme;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Workflow\WorkflowScheme;

class CopyController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $workflowSchemeId = $request->get('id');
        $workflowScheme = WorkflowScheme::getMetaDataById($workflowSchemeId);

        if ($workflowScheme['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $emptyName = false;
        $duplicateName = false;

        if ($request->request->has('copy_workflow_scheme')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));

            if (empty($name)) {
                $emptyName = true;
            }

            $workflowSchemeAlreadyExisting = WorkflowScheme::getByClientIdAndName(
                $session->get('client/id'),
                mb_strtolower($name)
            );

            if ($workflowSchemeAlreadyExisting) {
                $duplicateName = true;
            }

            if (!$emptyName && !$workflowSchemeAlreadyExisting) {
                $copiedWorkflowScheme = new WorkflowScheme($session->get('client/id'), $name, $description);

                $currentDate = Util::getServerCurrentDateTime();
                $copiedWorkflowSchemeId = $copiedWorkflowScheme->save($currentDate);

                $workflowSchemeData = WorkflowScheme::getDataById($workflowSchemeId);

                while ($workflowSchemeData && $data = $workflowSchemeData->fetch_array(MYSQLI_ASSOC)) {
                    $copiedWorkflowScheme->addData($copiedWorkflowSchemeId, $data['workflow_id'], $currentDate);
                }

                $this->getRepository(UbirimiLog::class)->add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_YONGO,
                    $session->get('user/id'),
                    'Copy Yongo Workflow Scheme ' . $workflowScheme['name'],
                    $currentDate
                );

                return new RedirectResponse('/yongo/administration/workflows/schemes');
            }
        }

        $menuSelectedCategory = 'issue';

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Copy Workflow Scheme';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/workflow/scheme/Copy.php', get_defined_vars());
    }
}
