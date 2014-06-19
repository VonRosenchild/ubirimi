<?php

namespace Ubirimi\Calendar\Service;

use Ubirimi\Container\ServiceInjectorInterface;

class CalendarServiceInjector implements ServiceInjectorInterface
{
    public static function inject(\Pimple $pimple)
    {
        $pimple['calendar.email'] = $pimple->share(function($pimple) {
            return new EmailService($pimple['api.client'], $pimple['session']);
        });
    }
}