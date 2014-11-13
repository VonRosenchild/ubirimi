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

class IssueType
{
    public function getAll($clientId) {
        $query = "SELECT issue_type.* " .
                 "FROM issue_type " .
                 "where issue_type.client_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $clientId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getAllSubTasks($clientId) {
        $query = "SELECT issue_type.* " .
                 "FROM issue_type " .
                 "where issue_type.client_id = ? and sub_task_flag = 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $clientId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getById($Id) {
        $query = "SELECT id, name, description, client_id FROM issue_type WHERE id = ? LIMIT 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $Id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return null;
    }

    public function updateById($Id, $name, $description, $date) {
        $query = 'UPDATE issue_type SET ' .
                 'name = ?, description = ?, date_updated = ? ' .
                 'WHERE id = ? ' .
                 'LIMIT 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("sssi", $name, $description, $date, $Id);
        $stmt->execute();
    }

    public function getSchemesForIssueTypeId($typeId, $type) {
        $query = "select issue_type_scheme.name, issue_type_scheme.description, issue_type_scheme.id " .
            "from issue_type " .
            "left join issue_type_scheme_data on issue_type_scheme_data.issue_type_id = issue_type.id " .
            "left join issue_type_scheme on issue_type_scheme.id = issue_type_scheme_data.issue_type_scheme_id " .
            "where issue_type.id = ? " .
            "and issue_type_scheme.type = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("is", $typeId, $type);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getByProjects($projectIdsArray) {
        $query = "select distinct issue_type.id, issue_type.name " .
            "from project " .
            "left join issue_type_scheme_data on issue_type_scheme_data.issue_type_scheme_id = project.issue_type_scheme_id " .
            "left join issue_type on issue_type.id = issue_type_scheme_data.issue_type_id " .
            "where project.id IN (" . implode(',', $projectIdsArray) . ')';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function deleteById($Id) {
        $query = 'delete from issue_type where id = ? limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $Id);
        $stmt->execute();

        $query = 'delete from field_issue_type_data where issue_type_id = ?';
        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $Id);
        $stmt->execute();

        $query = 'delete from issue_type_field_configuration_data where issue_type_id = ?';
        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $Id);
        $stmt->execute();

        $query = 'delete from issue_type_screen_scheme_data where issue_type_id = ?';
        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $Id);
        $stmt->execute();
    }
}
