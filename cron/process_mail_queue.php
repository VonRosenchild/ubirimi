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

use Ubirimi\Repository\Email\EmailQueue;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\SMTPServer;
use Ubirimi\Util;

/* check locking mechanism */
if (file_exists(__DIR__ . '/process_mail_queue.lock')) {
    $fp = fopen('process_mail_queue.lock', 'w+');
    if (!flock($fp, LOCK_EX | LOCK_NB)) {
        echo "Unable to obtain lock for process_mail_queue task.\n";
        exit(-1);
    }
}

require_once __DIR__ . '/../web/bootstrap_cli.php';

$emails = UbirimiContainer::get()['repository']->get(EmailQueue::class)->getBatch();

while ($emails && $email = $emails->fetch_array(MYSQLI_ASSOC)) {
    $smtpSettings = UbirimiContainer::get()['repository']->get(SMTPServer::class)->getByClientId($email['client_id']);
    if (null == $smtpSettings) {
        $smtpSettings = Util::getUbirimiSMTPSettings();
    }

    try {
        echo 'Process email Id: ' . $email['id'] . "\n";
        UbirimiContainer::get()['repository']->get(EmailQueue::class)->send($smtpSettings, $email);
        UbirimiContainer::get()['repository']->get(EmailQueue::class)->deleteById($email['id']);
    } catch (Swift_TransportException $e) {
        echo $e->getMessage() . "\n";
    } catch (Swift_IoException $e) {
        echo $e->getMessage() . "\n";
    } catch (Swift_RfcComplianceException $e) {
        echo $e->getMessage() . "\n";
    } catch (\Exception $e) {
        echo $e->getMessage() . "\n";
    }
}

if (null !== $fp) {
    fclose($fp);
}