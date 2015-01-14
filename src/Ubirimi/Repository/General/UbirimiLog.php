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

namespace Ubirimi\Repository\General;

use Ubirimi\Container\UbirimiContainer;

class UbirimiLog
{
    public function add($clientId, $userId, $message) {
        $query = "INSERT INTO general_log(client_id, user_id, message, date_created) VALUES (?, ?, ?, NOW())";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("iis", $clientId, $userId, $message);
        $stmt->execute();

        return UbirimiContainer::get()['db.connection']->insert_id;
    }

    public function getAll() {
        $query = "select general_log.id, general_log.message, general_log.date_created, " .
                 "client.company_domain, general_user.first_name, general_user.last_name " .
            "from general_log " .
            "left join general_user on general_user.id = general_log.user_id " .
            "left join client on client.id = general_user.client_id " .
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
        $query = "select general_log.id, general_log.message, general_log.date_created, " .
            "client.company_domain, general_user.first_name, general_user.last_name " .
            "from general_log " .
            "left join general_user on general_user.id = general_log.user_id " .
            "left join client on client.id = general_user.client_id " .
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
