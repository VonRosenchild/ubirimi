<?php

namespace Ubirimi\FrontendCOM\Controller;

use Ubirimi\UbirimiController;

class PrivacyController extends UbirimiController
{
    public function indexAction()
    {
        $content = 'Privacy.php';
        $page = 'privacy';

        return $this->render(__DIR__ . '/../Resources/views/_main.php', get_defined_vars());
    }
}


