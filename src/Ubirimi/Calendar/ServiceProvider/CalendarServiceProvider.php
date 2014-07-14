<?php

namespace Ubirimi\Calendar\ServiceProvider;

use Ubirimi\Container\ServiceProviderInterface;
use Ubirimi\Calendar\Service\EmailService;

class CalendarServiceProvider implements ServiceProviderInterface
{
    public function register(\Pimple $pimple)
    {
        $pimple['calendar.email'] = $pimple->share(function($pimple) {
            return new EmailService($pimple['session']);
        });
    }

    public function boot(\Pimple $pimple)
    {

    }
}