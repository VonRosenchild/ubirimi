<?php

namespace Ubirimi\FrontendCOM\Controller;

use Ubirimi\UbirimiController;

class PricingController extends UbirimiController
{
    public function indexAction()
    {
        $content = 'Pricing.php';
        $page = 'pricing';

        return $this->render(__DIR__ . '/../Resources/views/_main.php', get_defined_vars());
    }
}
