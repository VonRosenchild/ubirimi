<?php

namespace Ubirimi\Yongo\Controller\Administration\User;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Repository\User\User;

class AssignGroupsController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $userId = $request->request->get('user_id');
        $assignedGroups = $request->request->get('assigned_groups');

        $this->getRepository('ubirimi.user.user')->deleteGroupsByUserId($userId);

        if ($assignedGroups != -1) {
            $this->getRepository('ubirimi.user.user')->addGroups($userId, $assignedGroups);
        }

        return new Response('');
    }
}
