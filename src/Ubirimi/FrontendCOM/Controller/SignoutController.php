<?php

namespace Ubirimi\FrontendCOM\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;

class SignoutController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $session->invalidate();

        return new RedirectResponse('http://' . $_SERVER['HTTP_HOST'] . '/sign-in');
    }
}

