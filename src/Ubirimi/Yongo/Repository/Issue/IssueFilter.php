<?php

namespace Ubirimi\Yongo\Repository\Issue;

use Ubirimi\Container\UbirimiContainer;

class IssueFilter
{
    public static function updateById($filterId, $name, $description, $definition, $date) {
        $query = "UPDATE filter set name = ?, description = ?, definition = ?, date_updated = ? where id = ? LIMIT 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ssssi", $name, $description, $definition, $date, $filterId);
        $stmt->execute();
    }

    public static function getById($Id) {
        $query = 'SELECT id, user_id, description, definition, name from filter where id = ? ';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("i", $Id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return null;
    }

    public static function save($userId, $name, $description, $definition, $date) {
        $query = "INSERT INTO filter(user_id, name, description, definition, date_created) VALUES (?, ?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("issss", $userId, $name, $description, $definition, $date);

        $stmt->execute();

        return UbirimiContainer::get()['db.connection']->insert_id;
    }

    public static function getAllByUser($loggedInUserId) {
        $query = 'SELECT id, user_id, name, description, definition, date_created from filter where user_id = ? ';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("i", $loggedInUserId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public static function getAllByClientId($clientId) {
        $query = 'SELECT filter.id, user_id, filter.name, description, definition, filter.date_created ' .
                 'from filter ' .
                 'left join user on user.id = filter.user_id ' .
                 'where user.client_id = ? ';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $clientId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public static function deleteById($filterId) {
        $query = 'delete from filter where id = ? limit 1 ';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("i", $filterId);
        $stmt->execute();
    }
}
