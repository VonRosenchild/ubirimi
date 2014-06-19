<?php

namespace Ubirimi\Repository;
use Ubirimi\Container\UbirimiContainer;

class SMTPServer {

    const PROTOCOL_SMTP = 1;
    const PROTOCOL_SECURE_SMTP = 2;

    public static function add($clientId, $name, $description, $fromAddress, $emailPrefix, $protocol, $hostname, $port, $timeout, $tls, $username, $password, $defaultUbirimiServer = 0, $date) {
        $query = "INSERT INTO client_smtp_settings(client_id, name, description, from_address, email_prefix, smtp_protocol, hostname, port, timeout,
                    tls_flag, username, password, default_ubirimi_server_flag, date_created) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("issssisiiissis", $clientId, $name, $description, $fromAddress, $emailPrefix, $protocol, $hostname, $port, $timeout, $tls, $username, $password, $defaultUbirimiServer, $date);
            $stmt->execute();
        }
    }

    public static function getByClientId($clientId) {
        $query = 'SELECT * from client_smtp_settings where client_id = ? limit 1';

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("i", $clientId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows) {
                return $result->fetch_array(MYSQLI_ASSOC);
            } else
                return null;
        }
    }

    public static function updateById($smtpServerId, $name, $description, $fromAddress, $emailPrefix, $protocol, $hostname, $port, $timeout, $tls, $username, $password, $date) {
        $query = "update client_smtp_settings set name = ?, description = ?, from_address = ?, email_prefix = ?, smtp_protocol = ?,
                    hostname = ?, port = ?, timeout = ?, tls_flag = ?, username = ?, password = ?, date_updated = ?
                    where id = ?
                    limit 1";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("ssssisiiisssi", $name, $description, $fromAddress, $emailPrefix, $protocol, $hostname, $port, $timeout, $tls, $username, $password, $date, $smtpServerId);
            $stmt->execute();
        }
    }

    public static function getById($smtpServerId) {
        $query = 'SELECT * from client_smtp_settings where id = ? limit 1';

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("i", $smtpServerId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows) {
                return $result->fetch_array(MYSQLI_ASSOC);
            } else
                return null;
        }
    }

    public static function deleteById($smtpServerId) {
        $query = 'delete from client_smtp_settings where id = ? limit 1';

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("i", $smtpServerId);
            $stmt->execute();
        }
    }
}