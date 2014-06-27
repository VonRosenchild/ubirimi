<?php

namespace Ubirimi\Agile\Controller\Board\Column;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;

class AddConfirmController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        return $this->render(__DIR__ . '/../../../Resources/views/board/column/AddConfirm.php', get_defined_vars());
    }
}
