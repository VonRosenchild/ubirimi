<?php

namespace Ubirimi\Yongo\Repository\Issue;

use Ubirimi\Container\UbirimiContainer;

class Settings
{
    public static function createIssueType($clientId, $name, $description, $subTaskFlag, $iconName, $currentDate) {
        $query = "INSERT INTO issue_type(client_id, name, description, sub_task_flag, icon_name, date_created) VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ississ", $clientId, $name, $description, $subTaskFlag, $iconName, $currentDate);
        $stmt->execute();

        return UbirimiContainer::get()['db.connection']->insert_id;
    }

    public static function getAllIssueSettings($type, $clientId, $resultType = null) {
        $query = "SELECT * FROM issue_" . $type . ' where client_id = ?';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $clientId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            if ($resultType == 'array') {
                $resultArray = array();
                while ($setting = $result->fetch_array(MYSQLI_ASSOC)) {
                    $resultArray[] = $setting;
                }
                return $resultArray;
            } else return $result;

        } else
            return null;
    }

    public static function getByName($clientId, $setting_type, $name, $settingId = null) {
        $query = 'select id, name, description ' .
            'from issue_' . $setting_type . ' ' .
            'where client_id = ? ' .
            'and LOWER(name) = ? ';

        if ($settingId)
            $query .= 'and id != ?';

        $query .= ' limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        if ($settingId)
            $stmt->bind_param("isi", $clientId, $name, $settingId);
        else
            $stmt->bind_param("is", $clientId, $name);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return null;
    }

    public static function create($type, $clientId, $name, $description, $iconName = null, $color = null, $date) {
        if ($iconName) {
            if ($color) {
                $query = "INSERT INTO " . $type . "(client_id, name, description, icon_name, color, date_created) VALUES (?, ?, ?, ?, ?, ?)";

                $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
                $stmt->bind_param("isssss", $clientId, $name, $description, $iconName, $color, $date);
                $stmt->execute();
            } else {
                $query = "INSERT INTO " . $type . "(client_id, name, description, icon_name, date_created) VALUES (?, ?, ?, ?, ?)";

                $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
                $stmt->bind_param("issss", $clientId, $name, $description, $iconName, $date);
                $stmt->execute();
            }
        } else {
            if ($color) {
                $query = "INSERT INTO " . $type . "(client_id, name, description, color, date_created) VALUES (?, ?, ?, ?, ?)";

                $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
                $stmt->bind_param("issss", $clientId, $name, $description, $color, $date);
                $stmt->execute();
            } else {
                $query = "INSERT INTO " . $type . "(client_id, name, description, date_created) VALUES (?, ?, ?, ?)";

                $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
                $stmt->bind_param("isss", $clientId, $name, $description, $date);
                $stmt->execute();
            }
        }
    }

    public static function updateById($Id, $type, $name, $description, $color = null, $date) {
        $query = 'UPDATE issue_' . $type . ' SET ' .
                 'name = ?, description = ?, date_updated = ? ';
        if ($color)
            $query .= ", color = '" . $color . "' ";
        $query .= 'WHERE id = ? ' .
                  'LIMIT 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("sssi", $name, $description, $date, $Id);
        $stmt->execute();
    }

    public static function getById($Id, $settingType, $returnField = null) {
        $query = 'select * ' .
                 'from issue_' . $settingType . ' ' .
                 'where id = ? ' .
                 'limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $Id);
        $stmt->execute();
        $result = $stmt->get_result();

        $dataArrayResult = $result->fetch_array(MYSQLI_ASSOC);
        if (isset($returnField))
            return $dataArrayResult[$returnField];
        else
            return $dataArrayResult;
    }

    public static function deleteStatusById($Id) {
        $query = 'delete from issue_status where id = ? limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $Id);
        $stmt->execute();
    }

    public static function deleteResolutionById($Id) {
        $query = 'delete from issue_resolution where id = ? limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $Id);
        $stmt->execute();
    }

    public static function deletePriorityById($Id) {
        $query = 'delete from issue_priority where id = ? limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $Id);
        $stmt->execute();
    }
}
