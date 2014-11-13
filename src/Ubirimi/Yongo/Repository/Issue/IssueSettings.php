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

namespace Ubirimi\Yongo\Repository\Issue;

use Ubirimi\Container\UbirimiContainer;

class IssueSettings
{
    public function createIssueType($clientId, $name, $description, $subTaskFlag, $iconName, $currentDate) {
        $query = "INSERT INTO issue_type(client_id, name, description, sub_task_flag, icon_name, date_created) VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ississ", $clientId, $name, $description, $subTaskFlag, $iconName, $currentDate);
        $stmt->execute();

        return UbirimiContainer::get()['db.connection']->insert_id;
    }

    public function getAllIssueSettings($type, $clientId, $resultType = null) {
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

    public function getByName($clientId, $setting_type, $name, $settingId = null) {
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

    public function create($type, $clientId, $name, $description, $iconName = null, $color = null, $date) {
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

    public function updateById($Id, $type, $name, $description, $color = null, $date) {
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

    public function getById($Id, $settingType, $returnField = null) {
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

    public function deleteStatusById($Id) {
        $query = 'delete from issue_status where id = ? limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $Id);
        $stmt->execute();
    }

    public function deleteResolutionById($Id) {
        $query = 'delete from issue_resolution where id = ? limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $Id);
        $stmt->execute();
    }

    public function deletePriorityById($Id) {
        $query = 'delete from issue_priority where id = ? limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $Id);
        $stmt->execute();
    }
}
