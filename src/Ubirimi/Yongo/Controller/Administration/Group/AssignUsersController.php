<?php

namespace Ubirimi\Yongo\Controller\Administration\Group;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Repository\Group\Group;

use Ubirimi\SystemProduct;

class AssignUsersController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $groupId = $request->request->get('group_id');
        $userArray = $request->request->get('user_arr');

        $group = $this->getRepository('ubirimi.user.group')->getMetadataById($groupId);
        $this->getRepository('ubirimi.user.group')->deleteDataByGroupId($groupId);

        $currentDate = Util::getServerCurrentDateTime();
        $this->getRepository('ubirimi.user.group')->addData($groupId, $userArray, $currentDate);

        $date = Util::getServerCurrentDateTime();
        $this->getRepository('ubirimi.general.log')->add(
            $session->get('client/id'),
            SystemProduct::SYS_PRODUCT_YONGO,
            $session->get('user/id'),
            'UPDATE Yongo Group Members ' . $group['name'],
            $date
        );

        return new Response('');
    }
}