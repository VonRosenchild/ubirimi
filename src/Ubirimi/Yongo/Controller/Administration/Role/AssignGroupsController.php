<?php

namespace Ubirimi\Yongo\Controller\Administration\Role;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class AssignGroupsController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $permissionRoleId = $request->request->get('role_id');
        $groupArrayIds = $request->request->get('group_arr');
        $projectId = $request->request->get('project_id');

        $currentDate = Util::getServerCurrentDateTime();
        $this->getRepository('yongo.project.project')->deleteGroupsByPermissionRole($projectId, $permissionRoleId);
        $this->getRepository('yongo.project.project')->addGroupsForPermissionRole($projectId, $permissionRoleId, $groupArrayIds, $currentDate);

        return new Response('');
    }
}
