<?php

namespace Ubirimi\Yongo\Controller\Administration\Group;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\Repository\User\UbirimiGroup;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;


class AssignUsersController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $groupId = $request->request->get('group_id');
        $userArray = $request->request->get('user_arr');

        $group = $this->getRepository(UbirimiGroup::class)->getMetadataById($groupId);
        $this->getRepository(UbirimiGroup::class)->deleteDataByGroupId($groupId);

        $currentDate = Util::getServerCurrentDateTime();
        $this->getRepository(UbirimiGroup::class)->addData($groupId, $userArray, $currentDate);

        $date = Util::getServerCurrentDateTime();
        $this->getRepository(UbirimiLog::class)->add(
            $session->get('client/id'),
            SystemProduct::SYS_PRODUCT_YONGO,
            $session->get('user/id'),
            'UPDATE Yongo Group Members ' . $group['name'],
            $date
        );

        return new Response('');
    }
}