<?php

use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\Client;
use Ubirimi\Util;
use Ubirimi\Repository\Log;
use Ubirimi\SystemProduct;


/* check locking mechanism */
if (file_exists('update_filter_subscription.lock')) {
    $fp = fopen('update_filter_subscription.lock', 'w+');
    if (!flock($fp, LOCK_EX | LOCK_NB)) {
        echo "Unable to obtain lock for update_filter_subscription task.\n";
        exit(-1);
    }
}

require_once __DIR__ . '/../../web/bootstrap_cli.php';


if (null !== $fp) {
    fclose($fp);
}