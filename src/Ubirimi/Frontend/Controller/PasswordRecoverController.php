<?php

namespace Ubirimi\FrontendNET\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;

class PasswordRecoverController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $session->remove('password_recover');

        $content = 'PasswordRecover.php';

        return $this->render(__DIR__ . '/../Resources/views/_main.php', get_defined_vars());
    }
}
