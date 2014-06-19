<?php

namespace Ubirimi\FrontendCOM\Controller\Product;

use Ubirimi\UbirimiController;

class AgileController extends UbirimiController
{
    public function indexAction()
    {
        $content = 'product/Agile.php';
        $page = null;

        return $this->render(__DIR__ . '/../../Resources/views/_main.php', get_defined_vars());
    }
}