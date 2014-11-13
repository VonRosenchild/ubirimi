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

namespace Ubirimi\Yongo\Repository\Project;

use Ubirimi\Container\UbirimiContainer;

class ProjectComponent
{
    public function getByIds($Ids) {
        $query = 'SELECT project_component.* ' .
            'FROM project_component ' .
            'WHERE id IN (' . implode(', ', $Ids) . ') ' .
            'order by id asc';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->execute();

        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getAll()
    {
        $query = 'SELECT project_component.*, project.name as project_name
            FROM project_component
            LEFT JOIN project on project.id = project_component.project_id
            order by id asc';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function deleteById($componentId)
    {
        $query = 'delete from issue_component where project_component_id = ?';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $componentId);
        $stmt->execute();

        $query = 'delete from project_component where id = ? limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $componentId);
        $stmt->execute();

        // delete also any subcomponents
        $query = 'delete from project_component where parent_id = ?';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $componentId);
        $stmt->execute();
    }

    public function deleteByProjectId($projectId)
    {
        $components = UbirimiContainer::get()['repository']->get(YongoProject::class)->getComponents($projectId);

        while ($components && $component = $components->fetch_array(MYSQLI_ASSOC)) {
            $componentId = $component['id'];
            ProjectComponent::deleteById($componentId);
        }
    }
}
