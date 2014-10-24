<?php

namespace Ubirimi\Documentador\Controller\Administration;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class ListUsersController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $clientId = $session->get('client/id');

        $filterGroupId = isset($_GET['group_id']) ? $_GET['group_id'] : null;

        $users = $this->getRepository(UbirimiClient::class)->getUsers($clientId, $filterGroupId);

        $menuSelectedCategory = 'doc_users';

        return $this->render(__DIR__ . '/../../Resources/views/administration/ListUsers.php', get_defined_vars());
    }
}