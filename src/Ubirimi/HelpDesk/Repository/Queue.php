<?php

namespace Ubirimi\Repository\HelpDesk;

use Ubirimi\Container\UbirimiContainer;

class Queue {

    public static function deleteByProjectId($Id) {
        $query = "delete from help_filter where project_id = ?";
        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $Id);
        $stmt->execute();
        $stmt->close();
    }

    public static function save($createdUserId, $projectId, $filterName, $filterDescription, $filterData, $columns, $date) {
        $query = "INSERT INTO help_filter(project_id, created_user_id, name, description, definition, columns, date_created) VALUES (?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("iisssss", $projectId, $createdUserId, $filterName, $filterDescription, $filterData, $columns, $date);

            $stmt->execute();

            return UbirimiContainer::get()['db.connection']->insert_id;
        }
    }

    public static function updateById($Id, $filterName, $filterDescription, $filterData, $date) {
        $query = "UPDATE help_filter set name = ?, description = ?, definition = ?, date_updated = ? where id = ? LIMIT 1";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("ssssi", $filterName, $filterDescription, $filterData, $date, $Id);
            $stmt->execute();
        }
    }

    public static function getByProjectId($projectId) {
        $query = 'SELECT * from help_filter where project_id = ?';

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("i", $projectId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result;
            else
                return null;
        }
    }

    public static function getById($Id) {
        $query = 'SELECT * from help_filter where id = ? limit 1';

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("i", $Id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result->fetch_array(MYSQLI_ASSOC);
            else
                return null;
        }
    }

    public static function deleteById($Id) {
        $query = "delete from help_filter where id = ? limit 1";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $Id);
            $stmt->execute();
            $stmt->close();
        }
    }

    public static function updateColumns($queueId, $columns) {
        $query = "update help_filter set columns = ? where id = ? limit 1";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("si", $columns, $queueId);
            $stmt->execute();
            return UbirimiContainer::get()['db.connection']->insert_id;
        }
    }

    public static function getByName($name, $projectId, $queueId = null) {
        $query = 'select id, name, description ' .
            'from help_filter ' .
            'where project_id = ? ' .
            'and LOWER(name) = ? ';

        if ($queueId)
            $query .= 'and id != ?';

        $query .= ' limit 1';

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            if ($queueId)
                $stmt->bind_param("isi", $projectId, $name, $queueId);
            else
                $stmt->bind_param("is", $projectId, $name);

            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result->fetch_array(MYSQLI_ASSOC);
            else
                return null;
        }
    }
}