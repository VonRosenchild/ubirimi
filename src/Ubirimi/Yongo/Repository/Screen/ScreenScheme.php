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

class ScreenScheme
{
    public $name;
    public $description;
    public $clientId;

    function __construct($clientId = null, $name = null, $description = null) {
        $this->clientId = $clientId;
        $this->name = $name;
        $this->description = $description;

        return $this;
    }

    public function save($currentDate) {
        $query = "INSERT INTO screen_scheme(client_id, name, description, date_created) VALUES (?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("isss", $this->clientId, $this->name, $this->description, $currentDate);
        $stmt->execute();

        return UbirimiContainer::get()['db.connection']->insert_id;
    }

    public function addData($screenSchemeId, $operationId, $screenId, $currentDate) {
        $query = "INSERT INTO screen_scheme_data(screen_scheme_id, sys_operation_id, screen_id, date_created) VALUES (?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("iiis", $screenSchemeId, $operationId, $screenId, $currentDate);
        $stmt->execute();
    }

    public function deleteDataByScreenSchemeId($Id) {
        $query = "delete from screen_scheme_data where screen_scheme_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $Id);
        $stmt->execute();
    }

    public function updateDataById($screenSchemeId, $operationId, $screenId) {
        $query = "update screen_scheme_data set screen_id = ? where screen_scheme_id = ? and sys_operation_id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("iii", $screenId, $screenSchemeId, $operationId);
        $stmt->execute();
    }

    public function updateMetaDataById($Id, $name, $description, $date) {
        $query = "update screen_scheme set name = ?, description = ?, date_updated = ? where id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("sssi", $name, $description, $date, $Id);
        $stmt->execute();
    }

    public function getDataByScreenSchemeId($screenSchemeId) {
        $query = "select screen_scheme_data.id, screen_scheme_data.sys_operation_id, screen_scheme_data.screen_id, screen.name as screen_name, sys_operation.name as operation_name " .
            "from screen_scheme_data " .
            "left join screen on screen.id = screen_scheme_data.screen_id " .
            "left join sys_operation on sys_operation.id = screen_scheme_data.sys_operation_id " .
            "where screen_scheme_data.screen_scheme_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $screenSchemeId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getDataByScreenSchemeIdAndSysOperationId($screenSchemeId, $sysOperationId) {
        $query = "select screen_scheme_data.id, screen_scheme_data.sys_operation_id, screen_scheme_data.screen_id, screen.name as screen_name, sys_operation.name as operation_name " .
            "from screen_scheme_data " .
            "left join screen on screen.id = screen_scheme_data.screen_id " .
            "left join sys_operation on sys_operation.id = screen_scheme_data.sys_operation_id " .
            "where screen_scheme_data.screen_scheme_id = ? and " .
                "screen_scheme_data.sys_operation_id = ? " .
            "limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $screenSchemeId, $sysOperationId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return null;
    }

    public function getDataByScreenDataId($Id) {
        $query = "select screen_scheme_data.id, screen_scheme_data.screen_scheme_id, screen_scheme_data.sys_operation_id, screen_scheme_data.screen_id, screen.name as screen_name, sys_operation.name as operation_name " .
            "from screen_scheme_data " .
            "left join screen on screen.id = screen_scheme_data.screen_id " .
            "left join sys_operation on sys_operation.id = screen_scheme_data.sys_operation_id " .
            "where screen_scheme_data.id = ? ";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $Id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return null;
    }

    public function getMetaDataById($Id) {
        $query = "select * " .
            "from screen_scheme " .
            "where id = ? " .
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

    public function getMetaDataByClientId($clientId) {
        $query = "select * from screen_scheme where client_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $clientId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getMetaDataByNameAndClientId($clientId, $name) {
        $query = "select * from screen_scheme where client_id = ? and LOWER(name) = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("is", $clientId, $name);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getByIssueType($issueTypeId, $clientId) {
        $query = "select screen_scheme.id, screen_scheme.name " .
                 "from screen_scheme " .
                 "left join issue_type_screen_scheme_data on issue_type_screen_scheme_data.screen_scheme_id = screen_scheme.id " .
                 "where screen_scheme.client_id = ? " .
                 "and issue_type_screen_scheme_data.issue_type_id = ? " .
                 "group by screen_scheme.id";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $clientId, $issueTypeId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getByScreenId($clientId, $screenId) {
        $query = "select screen_scheme.id, screen_scheme.name " .
            "from screen_scheme_data " .
            "left join screen_scheme on screen_scheme.id = screen_scheme_data.screen_scheme_id " .
            "where screen_scheme.client_id = ? " .
            "and screen_scheme_data.screen_id = ? " .
            "group by screen_scheme.id";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $clientId, $screenId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function deleteById($screenSchemeId) {
        $query = "delete from screen_scheme where id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $screenSchemeId);
        $stmt->execute();
    }

    public function deleteByClientId($clientId) {

        $screenSchemeRepository = UbirimiContainer::get()['repository']->get(ScreenScheme::class);

        $screenSchemes = $screenSchemeRepository->getMetaDataByClientId($clientId);
        while ($screenSchemes && $screenScheme = $screenSchemes->fetch_array(MYSQLI_ASSOC)) {
            $screenSchemeRepository->deleteDataByScreenSchemeId($screenScheme['id']);
            $screenSchemeRepository->deleteById($screenScheme['id']);
        }
    }
}
