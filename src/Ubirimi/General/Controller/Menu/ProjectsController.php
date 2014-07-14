<?php

namespace Ubirimi\General\Controller\Menu;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Repository\Client;
use Ubirimi\Yongo\Repository\Permission\Permission;
use Ubirimi\Yongo\Repository\Project\Project;
use Ubirimi\Repository\User\User;
use Ubirimi\SystemProduct;
use Ubirimi\Yongo\Repository\Permission\GlobalPermission;

class ProjectsController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        if (Util::checkUserIsLoggedIn()) {
            $selectedProjectId = $session->get('selected_project_id');
        } else {
            $httpHOST = Util::getHttpHost();
            $clientId = Client::getByBaseURL($httpHOST, 'array', 'id');
            $loggedInUserId = null;
            $selectedProjectId = null;
        }

        if ($session->get('selected_product_id') == SystemProduct::SYS_PRODUCT_YONGO) {
            $urlPrefix = '/yongo/project/';
            $projectsMenu = Client::getProjectsByPermission(
                $session->get('client/id'),
                $session->get('user/id'),
                Permission::PERM_BROWSE_PROJECTS,
                'array'
            );
        } else {
            $urlPrefix = '/helpdesk/customer-portal/project/';
            $projectsMenu = Client::getProjects(
                $session->get('client/id'),
                'array',
                null,
                1
            );
        }

        $selectedProjectMenu = null;
        if ($selectedProjectId)
            $selectedProjectMenu = Project::getById($selectedProjectId);

        $hasGlobalAdministrationPermission = User::hasGlobalPermission(
            $session->get('client/id'),
            $session->get('user/id'),
            GlobalPermission::GLOBAL_PERMISSION_YONGO_ADMINISTRATORS
        );

        $hasGlobalSystemAdministrationPermission = User::hasGlobalPermission(
            $session->get('client/id'),
            $session->get('user/id'),
            GlobalPermission::GLOBAL_PERMISSION_YONGO_SYSTEM_ADMINISTRATORS
        );

        return $this->render(__DIR__ . '/../../Resources/views/menu/Projects.php', get_defined_vars());
    }
}
