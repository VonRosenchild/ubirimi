<?php

namespace Ubirimi\General\Controller\Menu;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;

class HomeController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        return $this->render(__DIR__ . '/../../Resources/views/menu/Home.php', get_defined_vars());
    }
}
