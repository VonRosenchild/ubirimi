<?php

namespace Ubirimi\SvnHosting\Service;

use Ubirimi\Container\ServiceProviderInterface;

class SvnHostingServiceProvider implements ServiceProviderInterface
{
    public function register(\Pimple $pimple)
    {
        $pimple['svn.email'] = $pimple->share(function($pimple) {
            return new EmailService($pimple['session']);
        });
    }

    public function boot(\Pimple $pimple)
    {

    }
}