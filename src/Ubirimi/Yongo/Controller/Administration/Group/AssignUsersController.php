<?php

namespace Ubirimi\Yongo\Controller\Administration\Group;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Repository\Group\Group;
use Ubirimi\Repository\Log;
use Ubirimi\SystemProduct;

class AssignUsersController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $groupId = $request->request->get('group_id');
        $userArray = $request->request->get('user_arr');

        $group = Group::getMetadataById($groupId);
        Group::deleteDataByGroupId($groupId);

        $currentDate = Util::getServerCurrentDateTime();
        Group::addData($groupId, $userArray, $currentDate);

        $date = Util::getServerCurrentDateTime();
        Log::add(
            $session->get('client/id'),
            SystemProduct::SYS_PRODUCT_YONGO,
            $session->get('user/id'),
            'UPDATE Yongo Group Members ' . $group['name'],
            $date
        );

        return new Response('');
    }
}