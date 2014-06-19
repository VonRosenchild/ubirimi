<?php

namespace Ubirimi\FrontendCOM\Controller\Account;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Repository\Client;
use Ubirimi\Repository\User\User;
use Ubirimi\Util;

class HomeController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $clientData = Client::getById($session->get('client/id'));
        $installedFlag = $clientData['installed_flag'];

        $users = User::getByClientId($session->get('client/id'));

        $page = 'account_home';

        $content = 'account/Home.php';

        return $this->render(__DIR__ . '/../../Resources/views/_main.php', get_defined_vars());
    }
}
