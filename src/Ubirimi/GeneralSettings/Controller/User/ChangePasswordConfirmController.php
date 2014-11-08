<?php

namespace Ubirimi\General\Controller\User;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;

class ChangePasswordConfirmController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        return $this->render(__DIR__ . '/../../Resources/views/user/ChangePasswordConfirm.php', get_defined_vars());
    }
}

