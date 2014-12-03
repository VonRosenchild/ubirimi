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


use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\GeneralTaskQueue;

/* check locking mechanism */
if (file_exists(__DIR__ . '/process_install_client.lock')) {
    $fp = fopen('process_install_client.lock', 'w+');
    if (!flock($fp, LOCK_EX | LOCK_NB)) {
        echo "Unable to obtain lock for process_install_client task.\n";
        exit(-1);
    }
}

require_once __DIR__ . '/../web/bootstrap_cli.php';

$conn = UbirimiContainer::get()['db.connection'];

$pendingClients = UbirimiContainer::get()['repository']->get(GeneralTaskQueue::class)->getPendingClients();

if (!empty($pendingClients)) {
    foreach ($pendingClients as $pendingClient) {
        try {
            UbirimiContainer::get()['client']->add($pendingClient);
            UbirimiContainer::get()['repository']->get(GeneralTaskQueue::class)->delete($pendingClient['id']);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}

$conn->autocommit(true);

if (null !== $fp) {
    fclose($fp);
}