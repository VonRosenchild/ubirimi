<?php

use Ubirimi\Service\ConfigService;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Service\UbirimiInjector;
use Ubirimi\ServiceProvider\UbirimiCoreServiceProvider;

require_once __DIR__ . '/../vendor/autoload.php';

/* parse .properties file and make them available in the container */
$configs = ConfigService::process(__DIR__ . '/../app/config/config.properties');

/* register global configs to the container */
UbirimiContainer::loadConfigs($configs);
UbirimiContainer::register(new UbirimiCoreServiceProvider());