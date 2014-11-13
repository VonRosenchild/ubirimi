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

namespace Ubirimi\Yongo\Repository\Screen;

use Ubirimi\Container\UbirimiContainer;

class Screen
{
    private $name;
    private $description;
    private $clientId;

    function __construct($clientId = null, $name = null, $description = null) {
        $this->clientId = $clientId;
        $this->name = $name;
        $this->description = $description;
    }

    public function save($currentDate) {
        $query = "INSERT INTO screen(client_id, name, description, date_created) VALUES (?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("isss", $this->clientId, $this->name, $this->description, $currentDate);
        $stmt->execute();

        return UbirimiContainer::get()['db.connection']->insert_id;
    }

    public function getAll($clientId) {
        $query = "SELECT * " .
            "FROM screen " .
            "where screen.client_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $clientId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getByClientId($clientId) {
        $query = "SELECT * FROM screen where client_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $clientId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function addData($screenId, $fieldId, $position, $currentDate) {
        if ($position == null) {
            $position = UbirimiContainer::get()['repository']->get(Screen::class)->getLastOrderNumber($screenId);

            // todo: cred ca aici position ar trebui incrementat
        }

        $query = "INSERT INTO screen_data(screen_id, field_id, `position`, date_created) VALUES (?, ?, ?, ?)";
        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("iiis", $screenId, $fieldId, $position, $currentDate);
        $stmt->execute();
    }

    public function getAllBySchemeId($screenSchemeId) {
        $query = "select screen.id, screen.name, screen.description " .
            "from screen_scheme_data " .
            "left join screen on screen.id = screen_scheme_data.screen_id " .
            "where screen_scheme_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $screenSchemeId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getDataById($Id) {
        $query = "select screen_data.id, field.name as field_name, field.code as field_code, field.id as field_id, screen_data.position, field.system_flag, " .
                 "field.all_issue_type_flag, field.all_project_flag, sys_field_type.code as type_code, field.description " .
            "from screen_data " .
            "left join field on field.id = screen_data.field_id " .
            "left join sys_field_type on sys_field_type.id = field.sys_field_type_id " .
            "where screen_data.screen_id = " . $Id . " " .
            "order by `position`";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getMetaDataById($Id) {
        $query = "select * " .
            "from screen " .
            "where screen.id = ? " .
            "limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $Id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return null;
    }

    public function getByNameAndId($clientId, $name, $screenId = null) {
        $query = "select id " .
            "from screen " .
            "where screen.client_id = ? and name = ? ";
        if ($screenId)
            $query .= ' and id != ' . $screenId . ' ';

        $query .= "limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("is", $clientId, $name);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return null;
    }

    public function updateMetadataById($screenId, $name, $description, $date) {
        $query = "update screen set name = ?, description = ?, date_updated = ? " .
                 "where id = ? " .
                 "limit 1 ";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("sssi", $name, $description, $date, $screenId);
        $stmt->execute();
    }

    public function getByFieldId($clientId, $fieldId) {
        $query = "select screen.id, screen.name " .
            "from screen " .
            "left join screen_data on screen_data.screen_id = screen.id " .
            "where screen.client_id = ? and screen_data.field_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $clientId, $fieldId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function deleteDataByScreenIdAndFieldId($screenId, $fieldId) {
        $query = "delete from screen_data where screen_id = ? and field_id = ? LIMIT 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $screenId, $fieldId);
        $stmt->execute();
    }

    public function deleteDataByFieldId($fieldId) {
        $query = "delete from screen_data where field_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $fieldId);
        $stmt->execute();
    }

    public function checkFieldInScreen($clientId, $screenId, $fieldId) {
        $query = "select screen.id " .
            "from screen " .
            "left join screen_data on screen_data.screen_id = screen.id " .
            "where screen.client_id = ? and screen.id = ? and screen_data.field_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("iii", $clientId, $screenId, $fieldId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getByName($clientId, $screenName) {
        $query = "select screen.* " .
            "from screen " .
            "where screen.client_id = ? and screen.name = ? " .
            "limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("is", $clientId, $screenName);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return null;
    }

    public function deleteDataById($screenDataId) {
        $query = "delete from screen_data where id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $screenDataId);
        $stmt->execute();
    }

    public function deleteById($screenId) {
        $query = "delete from screen_data where screen_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $screenId);
        $stmt->execute();

        $query = "delete from screen where id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $screenId);
        $stmt->execute();
    }

    public function updatePositionForField($screenId, $fieldId, $position) {
        $field = UbirimiContainer::get()['repository']->get(Screen::class)->getFieldById($screenId, $fieldId);

        $field2 = UbirimiContainer::get()['repository']->get(Screen::class)->getFieldByOrder($screenId, $position);

        $query = "update screen_data set `position` = ? " .
            "where screen_id = ? and field_id = ? " .
            "limit 1 ";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("iii", $position, $screenId, $fieldId);
        $stmt->execute();

        $query = "update screen_data set `position` = ? " .
            "where screen_id = ? and field_id = ? " .
            "limit 1 ";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("iii", $field['position'], $screenId, $field2['field_id']);
        $stmt->execute();
    }

    public function getLastOrderNumber($screenId) {
        $query = "select `position` " .
            "from screen_data " .
            "where screen_data.screen_id = ? " .
            "order by `position` desc " .
            "limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $screenId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            $data = $result->fetch_array(MYSQLI_ASSOC);
            return $data['position'];
        } else
            return null;
    }

    public function getFieldById($screenId, $fieldId) {
        $query = "select screen_data.* " .
            "from screen_data " .
            "where screen_data.screen_id = ? and field_id = ? " .
            "limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $screenId, $fieldId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            return $result->fetch_array(MYSQLI_ASSOC);
        } else
            return null;
    }

    public function getFieldByOrder($screenId, $position) {
        $query = "select screen_data.* " .
            "from screen_data " .
            "where screen_data.screen_id = ? and `position` = ? " .
            "limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $screenId, $position);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            return $result->fetch_array(MYSQLI_ASSOC);
        } else
            return null;
    }
}
