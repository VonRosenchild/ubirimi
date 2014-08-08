<?php

namespace Ubirimi\Yongo\Repository\Project;

use Ubirimi\Container\UbirimiContainer;

class ProjectComponent {

    public static function getByIds($Ids) {

        $query = 'SELECT project_component.* ' .
            'FROM project_component ' .
            'WHERE id IN (' . implode(', ', $Ids) . ') ' .
            'order by id asc';

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->execute();

            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result;
            else
                return null;
        }
    }

    public static function getAll()
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

    public static function deleteById($componentId)
    {
        $query = 'delete from issue_component where project_component_id = ?';

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $componentId);
            $stmt->execute();
        }

        $query = 'delete from project_component where id = ? limit 1';

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $componentId);
            $stmt->execute();
        }

        // delete also any subcomponents
        $query = 'delete from project_component where parent_id = ?';

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $componentId);
            $stmt->execute();
        }
    }

    public static function deleteByProjectId($projectId)
    {
        $components = Project::getComponents($projectId);

        while ($component = $components->fetch_array(MYSQLI_ASSOC)) {
            $componentId = $component['id'];
            ProjectComponent::deleteById($componentId);

        }
    }
}