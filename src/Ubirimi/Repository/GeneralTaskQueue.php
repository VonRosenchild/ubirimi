<?php

namespace Ubirimi\Repository;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Util;

class GeneralTaskQueue {

    public static function savePendingClientData($data) {
        $currentData = Util::getServerCurrentDateTime();
        $query = "INSERT INTO general_task_queue(type, data, date_created, date_updated) VALUES " .
                    "(1, '" . $data . "', '" . $currentData . "', '" . $currentData . "');";

        UbirimiContainer::get()['db.connection']->query($query);
    }

    public static function getPendingClients() {
        $query = "SELECT *
                    FROM general_task_queue
                    WHERE type = 1
                    ORDER BY id asc";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows) {
                return $result->fetch_all(MYSQL_ASSOC);
            } else {
                return null;
            }
        }
    }

    public static function delete($id) {
        $query = "DELETE FROM general_task_queue WHERE id = " . $id;

        UbirimiContainer::get()['db.connection']->query($query);
    }
}