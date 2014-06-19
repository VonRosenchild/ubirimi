<?php

namespace Ubirimi\FrontendCOM\Controller;

use Ubirimi\UbirimiController;

class CompanyController extends UbirimiController
{
    public function indexAction()
    {
        $content = 'Company.php';
        $page = 'company';

        return $this->render(__DIR__ . '/../Resources/views/_main.php', get_defined_vars());
    }
}

