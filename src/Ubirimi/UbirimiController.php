<?php

namespace Ubirimi;

class UbirimiController
{
    public function render($path, $variables)
    {
        return array($path, $variables);
    }
}