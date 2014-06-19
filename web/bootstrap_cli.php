<?php

use Ubirimi\Service\Config;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Service\UbirimiInjector;

require_once __DIR__ . '/../vendor/autoload.php';

/* parse .properties file and make them available in the container */
$configs = Config::process(__DIR__ . '/../app/config/config.properties');

/* register global configs to the container */
UbirimiContainer::loadConfigs($configs);

UbirimiInjector::inject(UbirimiContainer::get());