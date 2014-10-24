<?php

namespace Ubirimi\Yongo\Controller\Administration\Group;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\Repository\User\UbirimiGroup;
use Ubirimi\UbirimiController;
use Ubirimi\Util;


class AssignUsersConfirmController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $groupId = $request->get('group_id');

        $group = $this->getRepository(UbirimiGroup::class)->getMetadataById($groupId);
        $allUsers = $this->getRepository(UbirimiClient::class)->getUsers($session->get('client/id'));
        $groupUsers = $this->getRepository(UbirimiGroup::class)->getDataByGroupId($groupId);

        $groupUsersArrayIds = array();

        while ($groupUsers && $user = $groupUsers->fetch_array(MYSQLI_ASSOC)) {
            $groupUsersArrayIds[] = $user['user_id'];
        }

        if ($groupUsers) {
            $groupUsers->data_seek(0);
        }

        $firstSelected = true;

        return $this->render(__DIR__ . '/../../../Resources/views/administration/group/AssignUsersConfirm.php', get_defined_vars());
    }
}