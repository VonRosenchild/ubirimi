<?php

namespace Ubirimi\Yongo\Service;

use Ubirimi\Container\ServiceInjectorInterface;

class YongoServiceInjector implements ServiceInjectorInterface
{
    public static function inject(\Pimple $pimple)
    {
        $pimple['issue'] = $pimple->share(function($pimple) {
            return new IssueService($pimple['db.connection']);
        });

        $pimple['issue.email'] = $pimple->share(function($pimple) {
            return new IssueEmailService($pimple['api.client'], $pimple['session'], $pimple['workflow']);
        });

        $pimple['workflow'] = $pimple->share(function($pimple) {
            return new WorkflowService($pimple['api.client'], $pimple['session']);
        });
    }
}