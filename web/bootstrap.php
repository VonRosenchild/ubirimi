<?php

use Ubirimi\Calendar\EventListener\CalendarEventSubscriber;
use Ubirimi\Calendar\Service\CalendarServiceInjector;
use Ubirimi\Component\EventListener\JsonResponseListener;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\EventListener\UbirimiSubscriber;
use Ubirimi\Service\Config;
use Ubirimi\Service\UbirimiInjector;
use Ubirimi\SVNHosting\EventListener\SVNHostingEventSubscriber;
use Ubirimi\SVNHosting\Service\SVNHostingServiceInjector;
use Ubirimi\Yongo\EventListener\IssueEventSubscriber;
use Ubirimi\Yongo\Service\YongoServiceInjector;
use Ubirimi\Service\RouteBootstrap;

require_once __DIR__ . '/../vendor/autoload.php';

/* parse .properties file and make them available in the container */
$configs = Config::process(__DIR__ . '/../app/config/config.properties');

/* register global configs to the container */
UbirimiContainer::loadConfigs($configs);

UbirimiInjector::inject(UbirimiContainer::get());

UbirimiContainer::get()['dispatcher']->addSubscriber(new JsonResponseListener());
UbirimiContainer::get()['dispatcher']->addSubscriber(new IssueEventSubscriber());
UbirimiContainer::get()['dispatcher']->addSubscriber(new UbirimiSubscriber());
UbirimiContainer::get()['dispatcher']->addSubscriber(new CalendarEventSubscriber());
UbirimiContainer::get()['dispatcher']->addSubscriber(new SVNHostingEventSubscriber());

YongoServiceInjector::inject(UbirimiContainer::get());
CalendarServiceInjector::inject(UbirimiContainer::get());
SVNHostingServiceInjector::inject(UbirimiContainer::get());

$routeBootstrapper = new RouteBootstrap();
$urlMatcher = $routeBootstrapper->bootstrap(UbirimiContainer::get()['app.cache'], UbirimiContainer::get()['deploy.on_demand']);