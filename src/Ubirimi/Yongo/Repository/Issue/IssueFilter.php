<?php

namespace Ubirimi\Yongo\Repository\Issue;

use Ubirimi\Container\UbirimiContainer;

class IssueFilter
{
    public function updateById($filterId, $name, $description, $definition, $date) {
        $query = "UPDATE filter set name = ?, description = ?, definition = ?, date_updated = ? where id = ? LIMIT 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ssssi", $name, $description, $definition, $date, $filterId);
        $stmt->execute();
    }

    public function getById($Id) {
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

    public function save($userId, $name, $description, $definition, $date) {
        $query = "INSERT INTO filter(user_id, name, description, definition, date_created) VALUES (?, ?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("issss", $userId, $name, $description, $definition, $date);

        $stmt->execute();

        return UbirimiContainer::get()['db.connection']->insert_id;
    }

    public function getAllByUser($loggedInUserId) {
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

    public function getAllByClientId($clientId) {
        $query = 'SELECT filter.id, user_id, filter.name, description, definition, filter.date_created ' .
                 'from filter ' .
                 'left join user on user.id = filter.user_id ' .
                 'where user.client_id = ?';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $clientId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function deleteById($filterId) {
        $query = 'delete from filter where id = ? limit 1 ';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("i", $filterId);
        $stmt->execute();
    }

    public function checkFilterIsFavouriteForUserId($filterId, $userId) {
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

    public function deleteFavouriteByFilterIdAndUserId($userId, $filterId) {
        $query = 'delete from filter_favourite where user_id = ? and filter_id = ? limit 1 ';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("ii", $userId, $filterId);
        $stmt->execute();
    }

    public function addFavourite($userId, $filterId, $date) {
        $query = "INSERT INTO filter_favourite(user_id, filter_id, date_created) VALUES (?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("iis", $userId, $filterId, $date);

        $stmt->execute();

        return UbirimiContainer::get()['db.connection']->insert_id;
    }

    public function toggleFavourite($userId, $filterId, $date) {
        $isFavourite = UbirimiContainer::get()['repository']->get(IssueFilter::class)->checkFilterIsFavouriteForUserId($filterId, $userId);
        if ($isFavourite) {
            UbirimiContainer::get()['repository']->get(IssueFilter::class)->deleteFavouriteByFilterIdAndUserId($userId, $filterId);
        } else {
            UbirimiContainer::get()['repository']->get(IssueFilter::class)->addFavourite($userId, $filterId, $date);
        }
    }

    public function getSubscriptions($filterId) {
        $query = "SELECT filter_subscription.id, filter_subscription.period, " .
            "user.id as user_id, user.first_name, user.last_name, " .
            "user_created.id as user_created_id, user_created.first_name as created_first_name, user_created.last_name as created_last_name, " .
            "`group`.id as group_id, `group`.name as group_name " .
            "FROM filter_subscription " .
            "left join user on user.id = filter_subscription.user_id " .
            "left join user as user_created on user_created.id = filter_subscription.user_created_id " .
            "left join `group` on `group`.id = filter_subscription.group_id " .
            "where " .
            "filter_subscription.filter_id = ?";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $filterId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result;
            else
                return null;
        }
    }

    public function addSubscription($filterId, $userCreatedId, $userId, $groupId, $cronExpression, $emailWhenEmptyFlag, $date) {
        $query = "INSERT INTO filter_subscription(filter_id, user_created_id, user_id, group_id, period, email_when_empty_flag, date_created) VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("iiiisis", $filterId, $userCreatedId, $userId, $groupId, $cronExpression, $emailWhenEmptyFlag, $date);

        $stmt->execute();

        return UbirimiContainer::get()['db.connection']->insert_id;
    }

    public function deleteSubscriptionById($subscriptionId) {
        $query = "delete from filter_subscription where id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $subscriptionId);

        $stmt->execute();
    }

    public function getSubscriptionById($subscriptionId) {
        $query = 'SELECT * ' .
            'from filter_subscription ' .
            'where id = ? ' .
            "limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $subscriptionId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return null;
    }

    public function getAllSubscriptions() {
        $query = 'SELECT * ' .
            'from filter_subscription';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }
}