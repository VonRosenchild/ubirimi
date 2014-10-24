<?php

namespace Ubirimi\Yongo\ServiceProvider;

use Ubirimi\Container\ServiceProviderInterface;
use Ubirimi\Yongo\Service\IssueEmailService;
use Ubirimi\Yongo\Service\IssueService;
use Ubirimi\Yongo\Service\ProjectService;
use Ubirimi\Yongo\Service\WorkflowService;

class YongoServiceProvider implements ServiceProviderInterface
{
    public function register(\Pimple $pimple)
    {
        $pimple['issue'] = $pimple->share(function($pimple) {
            return new IssueService($pimple['session']);
        });

        $pimple['issue.email'] = $pimple->share(function($pimple) {
            return new IssueEmailService($pimple['session'], $pimple['workflow']);
        });

        $pimple['workflow'] = $pimple->share(function($pimple) {
            return new WorkflowService($pimple['session']);
        });

        $pimple['project'] = $pimple->share(function($pimple) {
            return new ProjectService($pimple['session']);
        });
    }

    public function boot(\Pimple $pimple)
    {

    }
}