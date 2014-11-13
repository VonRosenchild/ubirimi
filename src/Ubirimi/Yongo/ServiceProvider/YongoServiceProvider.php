<?php

/*
 *  Copyright (C) 2012-2014 SC Ubirimi SRL <info-copyright@ubirimi.com>
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License version 2 as
 *  published by the Free Software Foundation.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301, USA.
 */

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