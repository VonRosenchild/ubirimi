<?php

namespace Ubirimi\Yongo\Controller\Administration\User;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class AssignGroupsController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $userId = $request->request->get('user_id');
        $assignedGroups = $request->request->get('assigned_groups');

        $this->getRepository(UbirimiUser::class)->deleteGroupsByUserId($userId);

        if ($assignedGroups != -1) {
            $this->getRepository(UbirimiUser::class)->addGroups($userId, $assignedGroups);
        }

        return new Response('');
    }
}
