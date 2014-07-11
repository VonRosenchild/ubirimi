<?php

namespace Ubirimi\Yongo\Repository\Project;

use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Yongo\Repository\Issue\IssueComponent;

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