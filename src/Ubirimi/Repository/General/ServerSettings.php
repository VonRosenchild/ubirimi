<?php

namespace Ubirimi\Repository\General;

use Ubirimi\Container\UbirimiContainer;

class ServerSettings
{
    public function updateMaintenanceMessage($message) {
        $query = "update server_settings set maintenance_server_message = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("s", $message);

        $stmt->execute();
    }

    public function get() {
        $query = 'SELECT * from server_settings';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            return $result->fetch_array(MYSQLI_ASSOC);
        } else
            return null;
    }
}
