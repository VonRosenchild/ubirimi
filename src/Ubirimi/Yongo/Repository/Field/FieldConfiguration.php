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

class FieldConfiguration {

    private $name;
    private $description;
    private $clientId;

    function __construct($clientId = null, $name = null, $description = null) {
        $this->clientId = $clientId;
        $this->name = $name;
        $this->description = $description;

        return $this;
    }

    public function save($currentDate) {
        $query = "INSERT INTO field_configuration(client_id, name, description, date_created) VALUES (?, ?, ?, ?)";
        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("isss", $this->clientId, $this->name, $this->description, $currentDate);
        $stmt->execute();

        return UbirimiContainer::get()['db.connection']->insert_id;
    }

    public function getByClientId($clientId) {
        $query = "SELECT * " .
            "FROM field_configuration " .
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

    public function getByName($clientId, $name) {
        $query = "SELECT * " .
            "FROM field_configuration " .
            "where client_id = ? and name = ? " .
            "limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("is", $clientId, $name);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return null;
    }

    public function getMetaDataById($Id) {
        $query = "select * " .
            "from field_configuration " .
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

    public function updateMetadataById($Id, $name, $description, $date) {
        $query = "update field_configuration set name = ?, description = ?, date_updated = ? " .
            "where id = ? " .
            "limit 1 ";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("sssi", $name, $description, $date, $Id);
        $stmt->execute();
    }

    public function getByIssueType($issueTypeId, $clientId) {
        $query = "select field_configuration.id, field_configuration.name " .
            "from field_configuration " .
            "left join issue_type_field_configuration_data on issue_type_field_configuration_data.field_configuration_id = field_configuration.id " .
            "where field_configuration.client_id = ? " .
            "and issue_type_field_configuration_data.issue_type_id = ? " .
            "group by field_configuration.id";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $clientId, $issueTypeId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getDataByConfigurationAndField($fieldConfigurationId, $fieldId) {
        $query = "select * " .
            "from field_configuration_data " .
            "where field_configuration_id = ? and field_id = ? " .
            "limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $fieldConfigurationId, $fieldId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return null;
    }

    public function getDataByConfigurationId($fieldConfigurationId) {
        $query = "select * " .
            "from field_configuration_data " .
            "where field_configuration_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $fieldConfigurationId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function updateFieldDescription($fieldConfigurationId, $fieldId, $description) {
        $query = "update field_configuration_data set field_description = ? " .
            "where field_configuration_id = ? and field_id = ? " .
            "limit 1 ";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("sii", $description, $fieldConfigurationId, $fieldId);
        $stmt->execute();
    }

    public function updateData($fieldConfigurationId, $fieldId, $visibleFlag, $requiredFlag) {
        if (isset($visibleFlag)) {
            if ($visibleFlag == 1) {
                $query = "update field_configuration_data set visible_flag = 1 " .
                    "where field_configuration_id = ? and field_id = ? " .
                    "limit 1 ";

                $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
                $stmt->bind_param("ii", $fieldConfigurationId, $fieldId);
                $stmt->execute();
            } else {
                $query = "update field_configuration_data set visible_flag = 0, required_flag = 0 " .
                    "where field_configuration_id = ? and field_id = ? " .
                    "limit 1 ";

                $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
                $stmt->bind_param("ii", $fieldConfigurationId, $fieldId);
                $stmt->execute();
            }
        }

        if (isset($requiredFlag)) {
            $query = "update field_configuration_data set required_flag = ? " .
                "where field_configuration_id = ? and field_id = ? " .
                "limit 1 ";

            $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
            $stmt->bind_param("iii", $requiredFlag, $fieldConfigurationId, $fieldId);
            $stmt->execute();
        }
    }

    public function addSimpleData($fieldConfigurationId, $fieldId) {
        $query = "INSERT INTO field_configuration_data(field_configuration_id, field_id) VALUES (?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $fieldConfigurationId, $fieldId);
        $stmt->execute();

        return UbirimiContainer::get()['db.connection']->insert_id;
    }

    public function addCompleteData($fieldConfigurationId, $fieldId, $visibleFlag, $requiredFlag, $fieldDescription) {
        $query = "INSERT INTO field_configuration_data(field_configuration_id, field_id, visible_flag, required_flag, field_description) VALUES (?, ?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("iiiis", $fieldConfigurationId, $fieldId, $visibleFlag, $requiredFlag, $fieldDescription);
        $stmt->execute();

        return UbirimiContainer::get()['db.connection']->insert_id;
    }

    public function deleteDataByFieldConfigurationId($fieldConfigurationId) {
        $query = "delete from field_configuration_data where field_configuration_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $fieldConfigurationId);
        $stmt->execute();
    }

    public function deleteById($fieldConfigurationId) {
        $query = "delete from field_configuration where id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $fieldConfigurationId);
        $stmt->execute();
    }

    public function deleteByClientId($clientId) {
        $fieldConfigurations = UbirimiContainer::get()['repository']->get(FieldConfiguration::class)->getByClientId($clientId);

        while ($fieldConfigurations && $fieldConfiguration = $fieldConfigurations->fetch_array(MYSQLI_ASSOC)) {
            UbirimiContainer::get()['repository']->get(FieldConfiguration::class)->deleteDataByFieldConfigurationId($fieldConfiguration['id']);
            UbirimiContainer::get()['repository']->get(FieldConfiguration::class)->deleteById($fieldConfiguration['id']);
        }
    }

    public function getMetaDataByNameAndClientId($clientId, $name) {
        $query = "select * from field_configuration where client_id = ? and LOWER(name) = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("is", $clientId, $name);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }
}
