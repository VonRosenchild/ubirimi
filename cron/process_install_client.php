<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\GeneralTaskQueue;

/* check locking mechanism */
if (file_exists('process_install_client.lock')) {
    $fp = fopen('process_install_client.lock', 'w+');
    if (!flock($fp, LOCK_EX | LOCK_NB)) {
        echo "Unable to obtain lock for process_install_client task.\n";
        exit(-1);
    }
}

require_once __DIR__ . '/../web/bootstrap_cli.php';

$conn = UbirimiContainer::get()['db.connection'];

$pendingClients = GeneralTaskQueue::getPendingClients();

if (!empty($pendingClients)) {
    foreach ($pendingClients as $pendingClient) {
        try {
            UbirimiContainer::get()['client']->add($pendingClient);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}

$conn->autocommit(true);

if (null !== $fp) {
    fclose($fp);
}
