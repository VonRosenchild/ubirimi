<?php

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