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

class ListUsersController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $clientId = $session->get('client/id');

        $filterGroupId = isset($_GET['group_id']) ? $_GET['group_id'] : null;

        $users = $this->getRepository('ubirimi.general.client')->getUsers($clientId, $filterGroupId);

        $menuSelectedCategory = 'doc_users';

        require_once __DIR__ . '/../../Resources/views/administration/ListUsers.php';
    }
}