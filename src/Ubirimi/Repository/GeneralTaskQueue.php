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
use Ubirimi\Util;

class GeneralTaskQueue
{
    public function savePendingClientData($data) {
        $currentData = Util::getServerCurrentDateTime();
        $query = "INSERT INTO general_task_queue(type, data, date_created, date_updated) VALUES " .
                    "(1, '" . $data . "', '" . $currentData . "', '" . $currentData . "');";

        UbirimiContainer::get()['db.connection']->query($query);
    }

    public function getPendingClients() {
        $query = "SELECT *
                    FROM general_task_queue
                    WHERE type = 1
                    ORDER BY id asc";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return null;
        }
    }

    public function delete($id) {
        $query = "DELETE FROM general_task_queue WHERE id = " . $id;

        UbirimiContainer::get()['db.connection']->query($query);
    }
}