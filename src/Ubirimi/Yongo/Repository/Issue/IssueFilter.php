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

    public static function checkFilterIsFavouriteForUserId($filterId, $userId) {
        $query = "SELECT filter_favourite.id " .
            "FROM filter_favourite " .
            "where filter_favourite.user_id = ? and " .
            "filter_favourite.filter_id = ? " .
            "limit 1";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("ii", $userId, $filterId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result;
            else
                return null;
        }
    }

    public static function deleteFavouriteByFilterIdAndUserId($userId, $filterId) {
        $query = 'delete from filter_favourite where user_id = ? and filter_id = ? limit 1 ';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("ii", $userId, $filterId);
        $stmt->execute();
    }

    public static function addFavourite($userId, $filterId, $date) {
        $query = "INSERT INTO filter_favourite(user_id, filter_id, date_created) VALUES (?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("iis", $userId, $filterId, $date);

        $stmt->execute();

        return UbirimiContainer::get()['db.connection']->insert_id;
    }

    public static function toggleFavourite($userId, $filterId, $date) {
        $isFavourite = IssueFilter::checkFilterIsFavouriteForUserId($filterId, $userId);
        if ($isFavourite) {
            IssueFilter::deleteFavouriteByFilterIdAndUserId($userId, $filterId);
        } else {
            IssueFilter::addFavourite($userId, $filterId, $date);
        }
    }
}