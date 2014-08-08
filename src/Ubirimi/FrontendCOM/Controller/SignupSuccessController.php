<?php

namespace Ubirimi\FrontendCOM\Controller;

use Ubirimi\UbirimiController;

class SignupSuccessController extends UbirimiController
{
    public function indexAction()
    {
        $content = 'SignupSuccess.php';
        $page = 'signup-success';

        return $this->render(__DIR__ . '/../Resources/views/_main.php', get_defined_vars());
    }
}
