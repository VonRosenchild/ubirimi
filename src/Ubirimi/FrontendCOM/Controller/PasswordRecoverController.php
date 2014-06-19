<?php

namespace Ubirimi\FrontendCOM\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;

class PasswordRecoverController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $session->remove('password_recover');

        $content = 'PasswordRecover.php';
        $page = null;

        return $this->render(__DIR__ . '/../Resources/views/_main.php', get_defined_vars());
    }
}

