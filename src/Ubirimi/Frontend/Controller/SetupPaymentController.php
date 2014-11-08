<?php

namespace Ubirimi\Frontend\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;

class SetupPaymentController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $content = 'SetupPayment.php';

        return $this->render(__DIR__ . '/../Resources/views/_main.php', get_defined_vars());
    }
}
