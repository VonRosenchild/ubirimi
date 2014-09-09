<?php

namespace Ubirimi\Yongo\Controller\Administration\User;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Repository\User\User;
use Ubirimi\Repository\Group\Group;

class AssignGroupsConfirmController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $userId = $request->get('user_id');
        $productId = $request->get('product_id');

        $user = User::getById($userId);
        $allProductGroups = Group::getByClientIdAndProductId($session->get('client/id'), $productId);
        $userGroups = Group::getByUserIdAndProductId($userId, $productId);

        $user_groups_ids_arr = array();

        while ($userGroups && $group = $userGroups->fetch_array(MYSQLI_ASSOC))
            $user_groups_ids_arr[] = $group['id'];

        if ($userGroups)
            $userGroups->data_seek(0);

        $firstSelected = true;

        return $this->render(__DIR__ . '/../../../Resources/views/administration/user/AssignGroupsConfirm.php', get_defined_vars());
    }
}
