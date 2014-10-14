<?php

namespace Ubirimi\Yongo\Controller\Issue\Bulk;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Permission\Permission;

class ChooseOperationController extends UbirimiController
{

    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $menuSelectedCategory = 'issue';

        $operation = null;
        $operationSelected = true;
        if (isset($_POST['next_step_3'])) {

            foreach ($_POST as $key => $value) {
                if (substr($key, 0, 10) == "operation_") {
                    $operation = str_replace("operation_", "", $key);
                }
            }
            if ($operation) {
                UbirimiContainer::get()['session']->set('bulk_change_operation_type', $operation);
                header('Location: /yongo/issue/bulk-operation-details');
            } else {
                $operationSelected = false;
            }
        }

        $parameters = explode('&', UbirimiContainer::get()['session']->get('bulk_change_choose_issue_query_url'));
        for ($i = 0; $i < count($parameters); $i++) {
            $paramData = explode('=', $parameters[$i]);
            if ($paramData[0] == 'project') {
                $projectsIds = explode('|', $paramData[1]);

            }
        }

        // check for delete permission in each project
        $deletePermissionInAllProjects = true;
        for ($i = 0; $i < count($projectsIds); $i++) {
            $hasDeletePermission = $this->getRepository('yongo.project.project')->userHasPermission($projectsIds[$i], Permission::PERM_DELETE_ISSUE, $loggedInUserId);
            if (!$hasDeletePermission) {
                $deletePermissionInAllProjects = false;
                break;
            }
        }

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Bulk: Choose Operation';

        require_once __DIR__ . '/../../../Resources/views/issue/bulk/ChooseOperation.php';
    }
}