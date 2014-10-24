<?php

namespace Ubirimi\Yongo\Controller\Issue\Bulk;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Permission\Permission;
use Ubirimi\Yongo\Repository\Project\YongoProject;

class ChooseOperationController extends UbirimiController
{

    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $loggedInUserId = $session->get('user/id');

        $menuSelectedCategory = 'issue';

        $operation = null;
        $operationSelected = true;
        if ($request->request->has('next_step_3')) {

            foreach ($_POST as $key => $value) {
                if (substr($key, 0, 10) == "operation_") {
                    $operation = str_replace("operation_", "", $key);
                }
            }
            if ($operation) {
                UbirimiContainer::get()['session']->set('bulk_change_operation_type', $operation);
                return new RedirectResponse('/yongo/issue/bulk-operation-details');
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
            $hasDeletePermission = $this->getRepository(YongoProject::class)->userHasPermission($projectsIds[$i], Permission::PERM_DELETE_ISSUE, $loggedInUserId);
            if (!$hasDeletePermission) {
                $deletePermissionInAllProjects = false;
                break;
            }
        }

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Bulk: Choose Operation';

        return $this->render(__DIR__ . '/../../../Resources/views/issue/bulk/ChooseOperation.php', get_defined_vars());
    }
}