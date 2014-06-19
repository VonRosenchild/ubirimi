<?php

namespace Ubirimi\FrontendCOM\Controller\Product;

use Ubirimi\UbirimiController;

class EventsController extends UbirimiController
{
    public function indexAction()
    {
        $content = 'product/Events.php';
        $page = 'events';

        return $this->render(__DIR__ . '/../../Resources/views/_main.php', get_defined_vars());
    }
}