<?php

namespace Ubirimi\FrontendCOM\Controller\Product;

use Ubirimi\UbirimiController;

class DocumentadorController extends UbirimiController
{
    public function indexAction()
    {
        $content = 'product/Documentador.php';
        $page = 'documentador';

        return $this->render(__DIR__ . '/../../Resources/views/_main.php', get_defined_vars());
    }
}
