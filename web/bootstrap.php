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

use Ubirimi\Calendar\EventListener\CalendarEventSubscriber;
use Ubirimi\Component\EventListener\JsonResponseListener;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\EventListener\UbirimiSubscriber;
use Ubirimi\Service\ConfigService;
use Ubirimi\SvnHosting\EventListener\SvnHostingEventSubscriber;
use Ubirimi\SvnHosting\Service\SvnHostingServiceProvider;
use Ubirimi\Yongo\EventListener\IssueEventSubscriber;
use Ubirimi\Service\RouteBootstrapService;
use Ubirimi\ServiceProvider\UbirimiCoreServiceProvider;
use Ubirimi\Yongo\ServiceProvider\YongoServiceProvider;
use Ubirimi\Calendar\ServiceProvider\CalendarServiceProvider;

require_once __DIR__ . '/../vendor/autoload.php';

/* parse .properties file and make them available in the container */
$configs = ConfigService::process(__DIR__ . '/../app/config/config.properties');

/* register global configs to the container */
UbirimiContainer::loadConfigs($configs);

UbirimiContainer::register(new UbirimiCoreServiceProvider());
UbirimiContainer::register(new YongoServiceProvider());
UbirimiContainer::register(new CalendarServiceProvider());
UbirimiContainer::register(new SvnHostingServiceProvider());

UbirimiContainer::get()['dispatcher']->addSubscriber(new JsonResponseListener());
UbirimiContainer::get()['dispatcher']->addSubscriber(new IssueEventSubscriber());
UbirimiContainer::get()['dispatcher']->addSubscriber(new UbirimiSubscriber());
UbirimiContainer::get()['dispatcher']->addSubscriber(new CalendarEventSubscriber());
UbirimiContainer::get()['dispatcher']->addSubscriber(new SvnHostingEventSubscriber());

$routeBootstrapper = new RouteBootstrapService();
$urlMatcher = $routeBootstrapper->bootstrap(UbirimiContainer::get()['app.cache'], UbirimiContainer::get()['deploy.on_demand']);