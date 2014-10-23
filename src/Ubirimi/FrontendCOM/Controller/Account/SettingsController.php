<?php

namespace Ubirimi\FrontendCOM\Controller\Account;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class SettingsController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $page = 'account_settings';
        $clientId = $session->get('client/id');
        $content = 'account/Settings.php';

        return $this->render(__DIR__ . '/../../Resources/views/_main.php', get_defined_vars());
    }
}