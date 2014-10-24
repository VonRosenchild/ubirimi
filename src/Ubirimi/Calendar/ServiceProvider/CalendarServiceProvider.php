<?php

namespace Ubirimi\Calendar\ServiceProvider;

use Ubirimi\Calendar\Service\EmailService;
use Ubirimi\Container\ServiceProviderInterface;

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