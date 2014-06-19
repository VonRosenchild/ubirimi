<?php

namespace Ubirimi\Container;

interface ServiceInjectorInterface
{
    public static function inject(\Pimple $pimple);
}