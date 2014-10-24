<?php

namespace Ubirimi\Documentador\Controller\Administration\Group;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\User\UbirimiGroup;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class AssignUsersController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $groupId = $request->request->get('group_id');
        $userArray = $request->request->get('user_arr');
        $this->getRepository(UbirimiGroup::class)->deleteDataByGroupId($groupId);

        $currentDate = Util::getServerCurrentDateTime();
        $this->getRepository(UbirimiGroup::class)->addData($groupId, $userArray, $currentDate);

        return new Response('');
    }
}

