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

namespace Ubirimi\HelpDesk\Repository\Queue;

use Ubirimi\Container\UbirimiContainer;

class Queue
{
    public function deleteByProjectId($Id) {
        $query = "delete from help_filter where project_id = ?";
        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $Id);
        $stmt->execute();
        $stmt->close();
    }

    public function save($createdUserId, $projectId, $filterName, $filterDescription, $filterData, $columns, $date) {
        $query = "INSERT INTO help_filter(project_id, created_user_id, name, description, definition, columns, date_created) VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("iisssss", $projectId, $createdUserId, $filterName, $filterDescription, $filterData, $columns, $date);

        $stmt->execute();

        return UbirimiContainer::get()['db.connection']->insert_id;
    }

    public function updateById($Id, $filterName, $filterDescription, $filterData, $date) {
        $query = "UPDATE help_filter set name = ?, description = ?, definition = ?, date_updated = ? where id = ? LIMIT 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ssssi", $filterName, $filterDescription, $filterData, $date, $Id);
        $stmt->execute();
    }

    public function getByProjectId($projectId) {
        $query = 'SELECT * from help_filter where project_id = ?';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("i", $projectId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getById($Id) {
        $query = 'SELECT * from help_filter where id = ? limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("i", $Id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return null;
    }

    public function deleteById($Id) {
        $query = "delete from help_filter where id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $Id);
        $stmt->execute();
        $stmt->close();
    }

    public function updateColumns($queueId, $columns) {
        $query = "update help_filter set columns = ? where id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("si", $columns, $queueId);
        $stmt->execute();

        return UbirimiContainer::get()['db.connection']->insert_id;
    }

    public function getByName($name, $projectId, $queueId = null) {
        $query = 'select id, name, description ' .
            'from help_filter ' .
            'where project_id = ? ' .
            'and LOWER(name) = ? ';

        if ($queueId)
            $query .= 'and id != ?';

        $query .= ' limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
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
