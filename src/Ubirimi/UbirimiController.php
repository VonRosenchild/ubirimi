<?php

namespace Ubirimi;

use Ubirimi\Container\UbirimiContainer;

class UbirimiController
{
    public function getRepository($name)
    {
        return UbirimiContainer::get()['repository']->get($name);
    }

    public function render($path, $variables)
    {
        return array($path, $variables);
    }
}