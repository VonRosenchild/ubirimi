<?php

namespace Ubirimi\General\Repository\UserProfileCategory;

use Ubirimi\Container\UbirimiContainer;

class UserProfileCategory
{
    public static function getByClientId($clientId) {
        $query = "SELECT * FROM user_profile_category where client_id = ? order by name";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $clientId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public static function add($clientId, $name, $description, $date) {
        $query = "INSERT INTO user_profile_category(client_id, name, description, date_created) VALUES (?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("isss", $clientId, $name, $description, $date);
        $stmt->execute();
    }

    public static function getByName($clientId, $name, $userProfileCategoryId = null) {
        $query = 'select id, name from user_profile_category where client_id = ? and LOWER(name)= LOWER(?) ';

        if ($userProfileCategoryId) {
            $query .= 'and id != ?';
        }

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        if ($userProfileCategoryId) {
            $stmt->bind_param("isi", $clientId, $name, $userProfileCategoryId);
        } else {
            $stmt->bind_param("is", $clientId, $name);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return false;
    }
}
