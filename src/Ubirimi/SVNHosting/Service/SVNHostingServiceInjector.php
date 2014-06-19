<?php

namespace Ubirimi\SVNHosting\Service;

use Ubirimi\Container\ServiceInjectorInterface;

class SVNHostingServiceInjector implements ServiceInjectorInterface
{
    public static function inject(\Pimple $pimple)
    {
        $pimple['svn.email'] = $pimple->share(function($pimple) {
            return new EmailService($pimple['api.client'], $pimple['session']);
        });
    }
}