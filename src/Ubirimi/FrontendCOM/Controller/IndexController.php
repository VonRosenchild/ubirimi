<?php

namespace Ubirimi\FrontendCOM\Controller;

use Ubirimi\UbirimiController;

class IndexController extends UbirimiController
{
    public function indexAction()
    {
        $content = 'Index.php';
        $page = null;

        return $this->render(__DIR__ . '/../Resources/views/_home.php', get_defined_vars());
    }
}
