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

namespace Ubirimi\Yongo\Repository\Field;

use Ubirimi\Container\UbirimiContainer;

class FieldConfigurationScheme {

    public $name;
    public $description;
    public $clientId;

    function __construct($clientId = null, $name = null, $description = null) {
        $this->clientId = $clientId;
        $this->name = $name;
        $this->description = $description;

        return $this;
    }

    public function getByClient($clientId) {
        $query = "SELECT * " .
            "FROM issue_type_field_configuration " .
            "where client_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $clientId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function save($currentDate) {
        $query = "INSERT INTO issue_type_field_configuration(client_id, name, description, date_created) VALUES (?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("isss", $this->clientId, $this->name, $this->description, $currentDate);
        $stmt->execute();

        return UbirimiContainer::get()['db.connection']->insert_id;
    }

    public function addData($issueTypeFieldConfigurationId, $fieldConfigurationId, $issueTypeId, $currentDate) {
        $query = "INSERT INTO issue_type_field_configuration_data(issue_type_field_configuration_id, field_configuration_id, issue_type_id, date_created) VALUES (?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("iiis", $issueTypeFieldConfigurationId, $fieldConfigurationId, $issueTypeId, $currentDate);
        $stmt->execute();
    }

    public function deleteDataById($Id) {
        $query = "delete from issue_type_field_configuration_data where id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $Id);
        $stmt->execute();
    }

    public function deleteDataByFieldConfigurationSchemeId($Id) {
        $query = "delete from issue_type_field_configuration_data where issue_type_field_configuration_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $Id);
        $stmt->execute();
    }

    public function updateDataById($fieldConfigurationId, $issueTypeFieldConfigurationId, $issueTypeId) {
        $query = "update issue_type_field_configuration_data
                    set field_configuration_id = ?
                    where issue_type_field_configuration_id = ?
                      and issue_type_id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("iii", $fieldConfigurationId, $issueTypeFieldConfigurationId, $issueTypeId);
        $stmt->execute();
    }

    public function updateMetaDataById($Id, $name, $description, $date) {
        $query = "update issue_type_field_configuration
                    set name = ?, description = ?, date_updated = ?
                    where id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("sssi", $name, $description, $date, $Id);
        $stmt->execute();
    }

    public function getMetaDataById($Id) {
        $query = "select * " .
            "from issue_type_field_configuration " .
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

    public function getDataByFieldConfigurationSchemeId($Id) {
        $query = "select issue_type_field_configuration_data.id, issue_type_field_configuration_data.issue_type_id, " .
            "field_configuration.name as field_configuration_name, issue_type.name as issue_type_name, issue_type_field_configuration_data.field_configuration_id " .
            "from issue_type_field_configuration_data " .
            "left join issue_type on issue_type.id = issue_type_field_configuration_data.issue_type_id " .
            "left join field_configuration on field_configuration.id = issue_type_field_configuration_data.field_configuration_id " .
            "where issue_type_field_configuration_data.issue_type_field_configuration_id = ? ";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $Id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getDataById($Id) {
        $query = "select issue_type_field_configuration_data.id, issue_type_field_configuration_data.field_configuration_id, " .
                    "issue_type_field_configuration_data.issue_type_id, " .
                    "issue_type.name as issue_type_name, field_configuration.name as field_configuration_name, issue_type_field_configuration_data.issue_type_field_configuration_id " .
                 "from issue_type_field_configuration_data " .
                 "left join issue_type on issue_type.id = issue_type_field_configuration_data.issue_type_id " .
                 "left join field_configuration on field_configuration.id = issue_type_field_configuration_data.field_configuration_id " .
                 "where issue_type_field_configuration_data.id = ? ";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $Id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return null;
    }

    public function getFieldConfigurations($issueTypeFieldConfigurationId) {
        $query = "select field_configuration.id, field_configuration.name " .
            "from issue_type_field_configuration_data " .
            "left join field_configuration on field_configuration.id = issue_type_field_configuration_data.field_configuration_id " .
            "where issue_type_field_configuration_data.issue_type_field_configuration_id = ? " .
            "group by field_configuration.id";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $issueTypeFieldConfigurationId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getFieldConfigurationsSchemesByFieldConfigurationId($clientId, $fieldConfigurationId) {
        $query = "select issue_type_field_configuration.id, issue_type_field_configuration.name " .
            "from issue_type_field_configuration_data " .
            "left join issue_type_field_configuration on issue_type_field_configuration.id = issue_type_field_configuration_data.issue_type_field_configuration_id " .
            "where issue_type_field_configuration_data.field_configuration_id = ? and " .
            "issue_type_field_configuration.client_id = ? " .
            "group by issue_type_field_configuration_data.field_configuration_id";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $fieldConfigurationId, $clientId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getIssueTypesForFieldConfiguration($issueTypeFieldConfigurationId, $fieldConfigurationId) {
        $query = "select issue_type.id, issue_type.name " .
            "from issue_type_field_configuration_data " .
            "left join issue_type on issue_type.id = issue_type_field_configuration_data.issue_type_id " .
            "where issue_type_field_configuration_data.issue_type_field_configuration_id = ? and field_configuration_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $issueTypeFieldConfigurationId, $fieldConfigurationId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function deleteById($Id) {
        $query = "delete from issue_type_field_configuration where id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $Id);
        $stmt->execute();
    }

    public function deleteByClientId($clientId) {
        $fieldConfigurationSchemes = $this->getRepository(FieldConfigurationScheme::class)->getByClient($clientId);

        while ($fieldConfigurationSchemes && $fieldConfigurationScheme = $fieldConfigurationSchemes->fetch_array(MYSQLI_ASSOC)) {
            $this->getRepository(FieldConfigurationScheme::class)->deleteDataByFieldConfigurationSchemeId($fieldConfigurationScheme['id']);
            $this->getRepository(FieldConfigurationScheme::class)->deleteById($fieldConfigurationScheme['id']);
        }
    }

    public function getMetaDataByNameAndClientId($clientId, $name) {
        $query = "select * from issue_type_field_configuration where client_id = ? and LOWER(name) = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("is", $clientId, $name);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getByIssueTypeFieldConfigurationIdAndIssueTypeId($issueTypeFieldConfigurationId, $issueTypeId) {
        $query = "select issue_type_field_configuration.id, issue_type_field_configuration.name " .
            "from issue_type_field_configuration_data " .
            "where issue_type_field_configuration_data.issue_type_field_configuration_id = ? and " .
            "issue_type_field_configuration_data.issue_type_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $issueTypeFieldConfigurationId, $issueTypeId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return null;
    }
}
