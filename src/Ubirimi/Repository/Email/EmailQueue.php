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

namespace Ubirimi\Repository\Email;

use Swift_Message;
use Ubirimi\Container\UbirimiContainer;

class EmailQueue
{
    public function send($smtpSettings, $emailData) {
        $mailer = UbirimiContainer::get()['repository']->get(Email::class)->getMailer($smtpSettings);

        $message = Swift_Message::newInstance($emailData['subject'])
            ->setFrom(array($emailData['from_address']))
            ->setTo(array($emailData['to_address']))
            ->setBody($emailData['content'], 'text/html');

        @$mailer->send($message);
    }

    public function add($clientId, $fromAddress, $toAddress, $replyToAddress, $subject, $content, $date) {
        $query = "INSERT INTO general_mail_queue(client_id, from_address, to_address, reply_to_address, subject, content, date_created)
                  VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("issssss", $clientId, $fromAddress, $toAddress, $replyToAddress, $subject, $content, $date);
        $stmt->execute();
    }

    public function getBatch() {
        $query = 'SELECT * ' .
            'FROM general_mail_queue ' .
            "order by id asc " .
            "limit 20";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            return $result;
        } else {
            return null;
        }
    }

    public function deleteById($emailId) {
        $query = "delete from general_mail_queue where id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("i", $emailId);
        $stmt->execute();
    }

    public function getByClientId($clientId) {
        $query = 'SELECT * ' .
            'FROM general_mail_queue ' .
            "where client_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $clientId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            return $result;
        } else {
            return null;
        }
    }

    public function getAll() {
        $query = 'SELECT general_mail_queue.from_address, general_mail_queue.to_address, general_mail_queue.subject, general_mail_queue.content, general_mail_queue.date_created, ' .
                 'client.company_domain ' .
            'FROM general_mail_queue ' .
            'left join client on client.id = general_mail_queue.client_id';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            return $result;
        } else {
            return null;
        }
    }
}
