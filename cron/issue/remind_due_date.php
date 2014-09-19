<?php

use Ubirimi\Repository\Client;
use Ubirimi\Util;
use Ubirimi\Repository\Log;
use Ubirimi\SystemProduct;


/* check locking mechanism */
if (file_exists('remind_due_date.lock')) {
    $fp = fopen('remind_due_date.lock', 'w+');
    if (!flock($fp, LOCK_EX | LOCK_NB)) {
        echo "Unable to obtain lock for remind_due_date task.\n";
        exit(-1);
    }
}

require_once __DIR__ . '/../web/bootstrap_cli.php';

/*
 * select all users with remind_days_before_due_date not null and look to all issues assigned that have a due date
 */