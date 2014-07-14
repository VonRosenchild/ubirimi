<?php

namespace Ubirimi\Container;

interface ServiceProviderInterface
{
    public function register(\Pimple $pimple);

    public function boot(\Pimple $pimple);
}