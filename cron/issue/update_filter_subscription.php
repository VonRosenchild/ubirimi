<?php

use Ubirimi\Yongo\Repository\Issue\IssueFilter;
use Ubirimi\Container\UbirimiContainer;

/* check locking mechanism */
if (file_exists(__DIR__ . '/update_filter_subscription.lock')) {
    $fp = fopen('update_filter_subscription.lock', 'w+');
    if (!flock($fp, LOCK_EX | LOCK_NB)) {
        echo "Unable to obtain lock for update_filter_subscription task.\n";
        exit(-1);
    }
}

require_once __DIR__ . '/../../web/bootstrap_cli.php';

$filterSubscriptions = UbirimiContainer::get()['repository']->get(IssueFilter::class)->getAllSubscriptions();
$crontabLines = '*/10 * * * * /usr/bin/php ' . getcwd() . '/update_filter_subscription.php' . "\n";

while ($filterSubscriptions && $filterSubscription = $filterSubscriptions->fetch_array(MYSQLI_ASSOC)) {
    $crontabLines .= $filterSubscription['period'] . ' /usr/bin/php ' . getcwd() . '/run_filter_subscription.php ' . $filterSubscription['id'] . PHP_EOL;
}

system('echo "' . $crontabLines . '" | crontab');

if (null !== $fp) {
    fclose($fp);
}