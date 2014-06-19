<?php

namespace Ubirimi\FrontendCOM\Controller;

use Ubirimi\UbirimiController;

class TermsOfServiceController extends UbirimiController
{
    public function indexAction()
    {
        $content = 'TermsOfService.php';
        $page = 'termsofservice';

        return $this->render(__DIR__ . '/../Resources/views/_main.php', get_defined_vars());
    }
}
