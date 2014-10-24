<?php

namespace Ubirimi\Yongo\Controller\Issue;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\SystemOperation;
use Ubirimi\Yongo\Repository\Permission\Permission;
use Ubirimi\Yongo\Repository\Project\YongoProject;

class AddDialogController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $selectedProjectId = $session->get('selected_project_id');
        $sysOperationId = SystemOperation::OPERATION_CREATE;

        if (0 == $request->get('can_create')) {
            return new Response('<div class="infoBox">You do not have any projects with the permission to create an issue.</div>');
        }

        if ($session->get('selected_product_id') == SystemProduct::SYS_PRODUCT_YONGO) {
            $projects = $this->getRepository(UbirimiClient::class)->getProjectsByPermission(
                $session->get('client/id'),
                $session->get('user/id'),
                Permission::PERM_CREATE_ISSUE
            );
        } else {
            $projects = $this->getRepository(UbirimiClient::class)->getProjects($session->get('client/id'), null, null, true);
        }

        $projectData = $this->getRepository(YongoProject::class)->getById($selectedProjectId);
        $issueTypes = $this->getRepository(YongoProject::class)->getIssueTypes($selectedProjectId, 0);

        $firstIssueType = $issueTypes->fetch_array(MYSQLI_ASSOC);
        $issueTypeId = $firstIssueType['id'];
        $issueTypes->data_seek(0);

        $typeId = null;

        return $this->render(__DIR__ . '/../../Resources/views/issue/AddDialog.php', get_defined_vars());
    }
}
