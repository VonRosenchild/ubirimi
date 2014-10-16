<?php

namespace Ubirimi\Yongo\Controller\Administration\Workflow\Transition\PostFunction;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Workflow\Workflow;

class AddController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $workflowDataId = $request->get('id');
        $workflowData = $this->getRepository('yongo.workflow.workflow')->getDataById($workflowDataId);
        $workflow = $this->getRepository('yongo.workflow.workflow')->getMetaDataById($workflowData['workflow_id']);

        $postFunctions = WorkflowFunction::getAll();

        $errors = array('no_function_selected' => false);

        if (isset($_POST['add_new_post_function'])) {
            $functionId = isset($_POST['function']) ? $_POST['function'] : null;
            if ($functionId) {
                header('Location: /yongo/administration/workflow/transition-add-post-function-data/' . $workflowDataId . '?function_id=' . $functionId);
            } else {
                $errors['no_function_selected'] = true;
            }
        }
        $menuSelectedCategory = 'issue';

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Post Function';

        require_once __DIR__ . '/../../../../../Resources/views/administration/workflow/transition/post_function/Add.php';
    }
}