<?php

namespace Ubirimi\FrontendCOM\Controller\Product;

use Ubirimi\UbirimiController;

class ListController extends UbirimiController
{
    public function indexAction()
    {
        $content = 'product/List.php';
        $page = 'products';

        return $this->render(__DIR__ . '/../../Resources/views/_main.php', get_defined_vars());
    }
}