<?php

namespace Ubirimi\Yongo\Controller\Issue;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\Client;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Permission\Permission;
use Ubirimi\Yongo\Repository\Project\Project;

class AddDialogController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $canCreateIssue = $request->get('can_create');

        $selectedProjectId = $session->get('selected_project_id');

        if ($canCreateIssue) {
            if ($session->get('selected_product_id') == SystemProduct::SYS_PRODUCT_YONGO) {
                $projects = Client::getProjectsByPermission($session->get('client/id'), $session->get('user/id'), Permission::PERM_CREATE_ISSUE);
            } else {
                $projects = Client::getProjects($session->get('client/id'), null, null, true);
            }

            $projectData = Project::getById($selectedProjectId);
            $issueTypes = Project::getIssueTypes($selectedProjectId);

            $firstIssueType = $issueTypes->fetch_array(MYSQLI_ASSOC);
            $issueTypeId = $firstIssueType['id'];
            $issueTypes->data_seek(0);


            $typeId = null;
        }

        return $this->render(__DIR__ . '/../../Resources/views/issue/AddDialog.php', get_defined_vars());
    }
}
