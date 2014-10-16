<?php

namespace Ubirimi\Yongo\Controller\Administration\User;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Repository\User\User;


class AssignGroupsConfirmController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $userId = $request->get('user_id');
        $productId = $request->get('product_id');

        $user = $this->getRepository('ubirimi.user.user')->getById($userId);
        $allProductGroups = $this->getRepository('ubirimi.user.group')->getByClientIdAndProductId($session->get('client/id'), $productId);
        $userGroups = $this->getRepository('ubirimi.user.group')->getByUserIdAndProductId($userId, $productId);

        $user_groups_ids_arr = array();

        while ($userGroups && $group = $userGroups->fetch_array(MYSQLI_ASSOC))
            $user_groups_ids_arr[] = $group['id'];

        if ($userGroups)
            $userGroups->data_seek(0);

        $firstSelected = true;

        return $this->render(__DIR__ . '/../../../Resources/views/administration/user/AssignGroupsConfirm.php', get_defined_vars());
    }
}
