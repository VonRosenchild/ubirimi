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

namespace Ubirimi\Repository;

use Ubirimi\Container\UbirimiContainer;

class SMTPServer
{
    const PROTOCOL_SMTP = 1;
    const PROTOCOL_SECURE_SMTP = 2;

    public function add($clientId, $name, $description, $fromAddress, $emailPrefix, $protocol, $hostname, $port, $timeout, $tls, $username, $password, $defaultUbirimiServer = 0, $date) {
        $query = "INSERT INTO client_smtp_settings(client_id, name, description, from_address, email_prefix, smtp_protocol, hostname, port, timeout,
                    tls_flag, username, password, default_ubirimi_server_flag, date_created) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("issssisiiissis", $clientId, $name, $description, $fromAddress, $emailPrefix, $protocol, $hostname, $port, $timeout, $tls, $username, $password, $defaultUbirimiServer, $date);
        $stmt->execute();
    }

    public function getByClientId($clientId) {
        $query = 'SELECT * from client_smtp_settings where client_id = ? limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("i", $clientId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            return $result->fetch_array(MYSQLI_ASSOC);
        } else
            return null;
    }

    public function updateById($smtpServerId, $name, $description, $fromAddress, $emailPrefix, $protocol, $hostname, $port, $timeout, $tls, $username, $password, $date) {
        $query = "update client_smtp_settings set name = ?, description = ?, from_address = ?, email_prefix = ?, smtp_protocol = ?,
                    hostname = ?, port = ?, timeout = ?, tls_flag = ?, username = ?, password = ?, date_updated = ?
                    where id = ?
                    limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("ssssisiiisssi", $name, $description, $fromAddress, $emailPrefix, $protocol, $hostname, $port, $timeout, $tls, $username, $password, $date, $smtpServerId);
        $stmt->execute();
    }

    public function getById($smtpServerId) {
        $query = 'SELECT * from client_smtp_settings where id = ? limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $smtpServerId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            return $result->fetch_array(MYSQLI_ASSOC);
        } else
            return null;
    }

    public function deleteById($smtpServerId) {
        $query = 'delete from client_smtp_settings where id = ? limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("i", $smtpServerId);
        $stmt->execute();
    }
}
