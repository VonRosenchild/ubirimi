<?php

namespace Ubirimi\General\Repository\UserProfileCategory;

use Ubirimi\Container\UbirimiContainer;

class UserProfileCategory {

    public static function getByClientId($clientId) {
        $query = "SELECT * FROM user_profile_category where client_id = ? order by name";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $clientId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result;
            else
                return null;
        }
    }

    public static function add($clientId, $name, $description, $dateCreated) {

    }
}