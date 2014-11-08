<?php

namespace Ubirimi\General\Controller\Menu;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;

class SVNController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        return $this->render(__DIR__ . '/../../Resources/views/menu/SVN.php', get_defined_vars());
    }
}
