<?php

namespace Ubirimi\Documentador\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Documentador\Repository\Space\Space;
use Ubirimi\Documentador\Repository\Entity\Entity;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class AssignUsersController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $groupId = $_POST['group_id'];
        $userArray = $_POST['user_arr'];
        $this->getRepository('ubirimi.user.group')->deleteDataByGroupId($groupId);

        $currentDate = Util::getServerCurrentDateTime();
        $this->getRepository('ubirimi.user.group')->addData($groupId, $userArray, $currentDate);

        return new Response('');
    }
}

