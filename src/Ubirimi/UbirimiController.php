<?php

namespace Ubirimi;

use Symfony\Component\HttpFoundation\Response;
use Ubirimi\Container\UbirimiContainer;

class UbirimiController
{
    public function render($path, $variables)
    {
        return array($path, $variables);
    }
}