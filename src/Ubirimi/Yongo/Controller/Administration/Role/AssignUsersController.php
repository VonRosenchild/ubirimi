<?php

namespace Ubirimi\Yongo\Controller\Administration\Role;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class AssignUsersController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $permissionRoleId = $request->request->get('role_id');
        $userArray = $request->request->get('user_arr');
        $projectId = $request->request->get('project_id');

        $currentDate = Util::getServerCurrentDateTime();
        $this->getRepository('yongo.project.project')->deleteUsersByPermissionRole($projectId, $permissionRoleId);
        $this->getRepository('yongo.project.project')->addUsersForPermissionRole($projectId, $permissionRoleId, $userArray, $currentDate);

        return new Response('');
    }
}
