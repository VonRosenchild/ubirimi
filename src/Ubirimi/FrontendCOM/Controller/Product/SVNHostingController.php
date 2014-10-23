<?php

namespace Ubirimi\FrontendCOM\Controller\Product;

use Ubirimi\UbirimiController;

class SVNHostingController extends UbirimiController
{
    public function indexAction()
    {
        $content = 'product/SvnHosting.php';
        $page = 'svn';

        return $this->render(__DIR__ . '/../../Resources/views/_main.php', get_defined_vars());
    }
}