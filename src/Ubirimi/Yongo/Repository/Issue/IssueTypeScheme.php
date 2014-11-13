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

class IssueTypeScheme
{
    private $name;
    private $description;
    private $clientId;
    private $type;

    function __construct($clientId = null, $name = null, $description = null, $type = null) {
        $this->clientId = $clientId;
        $this->name = $name;
        $this->description = $description;
        $this->type = $type;

        return $this;
    }

    public function save($currentDate) {
        $query = "INSERT INTO issue_type_scheme(client_id, name, description, type, date_created) VALUES (?, ?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("issss", $this->clientId, $this->name, $this->description, $this->type, $currentDate);
        $stmt->execute();

        return UbirimiContainer::get()['db.connection']->insert_id;
    }

    public function getByClientId($clientId, $type = null) {
        $query = "select issue_type_scheme.id, issue_type_scheme.name, issue_type_scheme.description " .
                 "from issue_type_scheme " .
                 "where issue_type_scheme.client_id = ? ";

        if ($type)
            $query .= " and type = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        if ($type)
            $stmt->bind_param("is", $clientId, $type);
        else
            $stmt->bind_param("i", $clientId);

        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getById($issueTypeSchemeId) {
        $query = "select issue_type_scheme.id, issue_type_scheme.name, issue_type_scheme.description " .
                 "from issue_type_scheme " .
                 "where issue_type_scheme.id = ? " .
                 "limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $issueTypeSchemeId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return null;
    }

    public function getMetaDataById($Id) {
        $query = "select * " .
            "from issue_type_scheme " .
            "where issue_type_scheme.id = ? " .
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

    public function getDataById($Id) {
        $query = "select issue_type_scheme_data.id, issue_type.name, issue_type.description, issue_type_scheme_data.issue_type_id, issue_type.icon_name as issue_type_icon_name " .
            "from issue_type_scheme_data " .
            "left join issue_type on issue_type.id = issue_type_scheme_data.issue_type_id " .
            "where issue_type_scheme_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $Id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function updateMetaDataById($Id, $name, $description) {
        $query = "update issue_type_scheme set name = ?, description = ? where id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ssi", $name, $description, $Id);
        $stmt->execute();
    }

    public function deleteDataByIssueTypeSchemeId($Id) {
        $query = "delete from issue_type_scheme_data where issue_type_scheme_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $Id);
        $stmt->execute();
    }

    public function addData($issueTypeSchemeId, $issueTypeId, $currentDate) {
        $query = "INSERT INTO issue_type_scheme_data(issue_type_scheme_id, issue_type_id, date_created) VALUES (?, ?, ?)";
        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("iis", $issueTypeSchemeId, $issueTypeId, $currentDate);
        $stmt->execute();
    }

    public function deleteById($issueTypeSchemeId) {
        $query = "delete from issue_type_scheme where id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $issueTypeSchemeId);
        $stmt->execute();
    }

    public function deleteByClientId($clientId) {
        $issueTypeSchemes = IssueTypeScheme::getByClientId($clientId);
        while ($issueTypeSchemes && $issueTypeScheme = $issueTypeSchemes->fetch_array(MYSQLI_ASSOC)) {
            IssueTypeScheme::deleteDataByIssueTypeSchemeId($issueTypeScheme['id']);
            IssueTypeScheme::deleteById($issueTypeScheme['id']);
        }
    }

    public function getMetaDataByNameAndClientId($clientId, $name) {
        $query = "select * from issue_type_scheme where client_id = ? and LOWER(name) = ?";

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
