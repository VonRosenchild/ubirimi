<?php

namespace Ubirimi\FrontendCOM\Controller\Product;

use Ubirimi\UbirimiController;

class YongoController extends UbirimiController
{
    public function indexAction()
    {
        $content = 'product/Yongo.php';
        $page = 'yongo';

        return $this->render(__DIR__ . '/../../Resources/views/_main.php', get_defined_vars());
    }
}