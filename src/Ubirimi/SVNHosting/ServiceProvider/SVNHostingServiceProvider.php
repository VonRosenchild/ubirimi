<?php

namespace Ubirimi\SVNHosting\Service;

use Ubirimi\Container\ServiceProviderInterface;
use Ubirimi\SVNHosting\Service\EmailService;

class SVNHostingServiceProvider implements ServiceProviderInterface
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