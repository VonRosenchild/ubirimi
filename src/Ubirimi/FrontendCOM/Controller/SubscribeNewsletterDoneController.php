<?php

namespace Ubirimi\FrontendCOM\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\Newsletter;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class SubscribeNewsletterDoneController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $content = 'SubscribeNewsletterDone.php';
        $page = null;

        return $this->render(__DIR__ . '/../Resources/views/_main.php', get_defined_vars());
    }
}