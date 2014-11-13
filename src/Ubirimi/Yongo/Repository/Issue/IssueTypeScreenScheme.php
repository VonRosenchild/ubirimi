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

class IssueTypeScreenScheme
{
    private $name;
    private $description;
    private $clientId;
    private $currentDate;

    function __construct($clientId = null, $name = null, $description = null) {
        $this->clientId = $clientId;
        $this->name = $name;
        $this->description = $description;

        return $this;
    }

    public function save($currentDate) {
        $query = "INSERT INTO issue_type_screen_scheme(client_id, name, description, date_created) VALUES (?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("isss", $this->clientId, $this->name, $this->description, $currentDate);
        $stmt->execute();

        return UbirimiContainer::get()['db.connection']->insert_id;
    }

    public function getByClientId($clientId) {
        $query = "select issue_type_screen_scheme.id, issue_type_screen_scheme.name, issue_type_screen_scheme.description " .
            "from issue_type_screen_scheme " .
            "where issue_type_screen_scheme.client_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $clientId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getMetaDataById($Id) {
        $query = "select * " .
            "from issue_type_screen_scheme " .
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

    public function getDataById($Id) {
        $query = "select issue_type_screen_scheme_data.id, issue_type_screen_scheme_data.screen_scheme_id, issue_type_screen_scheme_data.issue_type_id, " .
                    "issue_type.name as issue_type_name, screen_scheme.name as screen_scheme_name, issue_type_screen_scheme_data.issue_type_screen_scheme_id " .
                 "from issue_type_screen_scheme_data " .
                 "left join issue_type on issue_type.id = issue_type_screen_scheme_data.issue_type_id " .
                 "left join screen_scheme on screen_scheme.id = issue_type_screen_scheme_data.screen_scheme_id " .
                 "where issue_type_screen_scheme_data.id = ? ";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $Id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return null;
    }

    public function getDataByIssueTypeScreenSchemeId($issueTypeScreenSchemeId) {
        $query = "select issue_type_screen_scheme_data.id, issue_type_screen_scheme_data.issue_type_id, issue_type_screen_scheme_data.screen_scheme_id, " .
                    "screen_scheme.name as screen_scheme_name, issue_type.name as issue_type_name " .
                 "from issue_type_screen_scheme_data " .
                 "left join issue_type on issue_type.id = issue_type_screen_scheme_data.issue_type_id " .
                 "left join screen_scheme on screen_scheme.id = issue_type_screen_scheme_data.screen_scheme_id " .
                 "where issue_type_screen_scheme_data.issue_type_screen_scheme_id = ? ";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $issueTypeScreenSchemeId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getDataByIssueTypeScreenSchemeIdAndIssueTypeId($issueTypeScreenSchemeId, $issueTypeId) {
        $query = "select issue_type_screen_scheme_data.id, issue_type_screen_scheme_data.issue_type_id, issue_type_screen_scheme_data.screen_scheme_id, " .
            "screen_scheme.name as screen_scheme_name, issue_type.name as issue_type_name " .
            "from issue_type_screen_scheme_data " .
            "left join issue_type on issue_type.id = issue_type_screen_scheme_data.issue_type_id " .
            "left join screen_scheme on screen_scheme.id = issue_type_screen_scheme_data.screen_scheme_id " .
            "where issue_type_screen_scheme_data.issue_type_screen_scheme_id = ? and " .
                "issue_type_screen_scheme_data.issue_type_id = ? " .
            "limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $issueTypeScreenSchemeId, $issueTypeId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return null;
    }

    public function updateMetaDataById($Id, $name, $description, $date) {
        $query = "update issue_type_screen_scheme set name = ?, description = ?, date_updated = ? where id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("sssi", $name, $description, $date, $Id);
        $stmt->execute();
    }

    public function deleteDataById($Id) {
        $query = "delete from issue_type_screen_scheme_data where id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $Id);
        $stmt->execute();
    }

    public function deleteDataByIssueTypeScreenSchemeId($issueTypeScreenSchemeId) {
        $query = "delete from issue_type_screen_scheme_data where issue_type_screen_scheme_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $issueTypeScreenSchemeId);
        $stmt->execute();
    }

    public function addData($screenSchemeId, $issueTypeId, $currentDate) {
        $query = "INSERT INTO issue_type_screen_scheme_data(issue_type_screen_scheme_id, issue_type_id, date_created) VALUES (?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("iis", $screenSchemeId, $issueTypeId, $currentDate);
        $stmt->execute();
    }

    public function addDataComplete($issueTypeScreenSchemeId, $issueTypeId, $screenSchemeId, $currentDate) {
        $query = "INSERT INTO issue_type_screen_scheme_data(issue_type_screen_scheme_id, issue_type_id, screen_scheme_id, date_created) VALUES (?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("isis", $issueTypeScreenSchemeId, $issueTypeId, $screenSchemeId, $currentDate);
        $stmt->execute();
    }

    public function updateDataById($screenSchemeId, $issueTypeId, $issueTypeScreenSchemeId) {
        $query = "update issue_type_screen_scheme_data set screen_scheme_id = ? where issue_type_screen_scheme_id = ? and issue_type_id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("iii", $screenSchemeId, $issueTypeScreenSchemeId, $issueTypeId);
        $stmt->execute();
    }

    public function getScreenSchemes($issueTypeScreenSchemeId) {
        $query = "select screen_scheme.id, screen_scheme.name " .
            "from issue_type_screen_scheme_data " .
            "left join screen_scheme on screen_scheme.id = issue_type_screen_scheme_data.screen_scheme_id " .
            "where issue_type_screen_scheme_data.issue_type_screen_scheme_id = ? " .
            "group by screen_scheme.id";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $issueTypeScreenSchemeId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getIssueTypesForScreenScheme($issueTypeScreenSchemeId, $screenSchemeId) {
        $query = "select issue_type.id, issue_type.name " .
            "from issue_type_screen_scheme_data " .
            "left join issue_type on issue_type.id = issue_type_screen_scheme_data.issue_type_id " .
            "where issue_type_screen_scheme_data.issue_type_screen_scheme_id = ? and screen_scheme_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $issueTypeScreenSchemeId, $screenSchemeId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function deleteById($issueTypeScreenSchemeId) {
        $query = "delete from issue_type_screen_scheme where id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $issueTypeScreenSchemeId);
        $stmt->execute();
    }

    public function deleteByClientId($clientId) {
        $issueTypeScreenSchemeRepository = UbirimiContainer::get()['repository']->get(IssueTypeScreenScheme::class);

        $issueTypeScreenSchemes = $issueTypeScreenSchemeRepository->getByClientId($clientId);
        while ($issueTypeScreenSchemes && $issueTypeScreenScheme = $issueTypeScreenSchemes->fetch_array(MYSQLI_ASSOC)) {
            $issueTypeScreenSchemeRepository->deleteDataByIssueTypeScreenSchemeId($issueTypeScreenScheme['id']);
            $issueTypeScreenSchemeRepository->deleteById($issueTypeScreenScheme['id']);
        }
    }

    public function getByScreenSchemeId($screenSchemeId) {
        $query = "select issue_type_screen_scheme.id, issue_type_screen_scheme.name " .
            "from issue_type_screen_scheme_data " .
            "left join issue_type_screen_scheme on issue_type_screen_scheme.id = issue_type_screen_scheme_data.issue_type_screen_scheme_id " .
            "where issue_type_screen_scheme_data.screen_scheme_id = ? " .
            "group by issue_type_screen_scheme_data.screen_scheme_id";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $screenSchemeId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getMetaDataByNameAndClientId($clientId, $name) {
        $query = "select * from issue_type_screen_scheme where client_id = ? and LOWER(name) = ?";

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
