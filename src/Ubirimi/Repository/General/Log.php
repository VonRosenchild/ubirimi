<?php

namespace Ubirimi\Repository\General;

use Ubirimi\Container\UbirimiContainer;

class Log
{
    public function add($clientId, $productId, $userId, $message) {
        $query = "INSERT INTO general_log(client_id, sys_product_id, user_id, message, date_created) VALUES (?, ?, ?, ?, NOW())";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("iiis", $clientId, $productId, $userId, $message);
        $stmt->execute();

        return UbirimiContainer::get()['db.connection']->insert_id;
    }

    public function getAll() {
        $query = "select general_log.id, general_log.message, general_log.date_created, " .
                 "client.company_domain, user.first_name, user.last_name " .
            "from general_log " .
            "left join user on user.id = general_log.user_id " .
            "left join client on client.id = user.client_id " .
            "order by id desc " .
            "limit 100";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            return $result;
        } else
            return null;
    }

    public function getByClientIdAndInterval($clientId, $from, $to) {
        $query = "select general_log.id, general_log.message, general_log.date_created, general_log.sys_product_id, " .
            "client.company_domain, user.first_name, user.last_name " .
            "from general_log " .
            "left join user on user.id = general_log.user_id " .
            "left join client on client.id = user.client_id " .
            "where client.id = ? and " .
            "DATE(general_log.date_created) >= ? and " .
            "DATE(general_log.date_created) <= ? " .
            "order by id desc";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("iss", $clientId, $from, $to);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            return $result;
        } else
            return null;
    }
}
