<?php

namespace Ubirimi\FrontendCOM\Controller\Product;

use Ubirimi\UbirimiController;

class HelpdeskController extends UbirimiController
{
    public function indexAction()
    {
        $content = 'product/Helpdesk.php';
        $page = null;

        return $this->render(__DIR__ . '/../../Resources/views/_main.php', get_defined_vars());
    }
}