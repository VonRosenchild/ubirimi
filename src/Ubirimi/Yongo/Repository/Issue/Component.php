<?php

namespace Ubirimi\Yongo\Repository\Issue;

use Ubirimi\Container\UbirimiContainer;

class Component
{
    public function deleteByIssueId($issueId) {
        $query = 'DELETE FROM issue_component WHERE issue_id = ?';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $issueId);
        $stmt->execute();
    }

    public function getByIssueIdAndProjectId($issueId, $projectId, $resultType = null, $resultColumn = null) {
        $query = 'SELECT issue_component.id, project_component.name, project_component_id, parent_id ' .
            'FROM issue_component ' .
            'LEFT JOIN project_component on issue_component.project_component_id = project_component.id ' .
            'WHERE issue_id = ? and project_component.project_id = ? ' .
            'order by id asc';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $issueId, $projectId);
        $stmt->execute();

        $result = $stmt->get_result();
        if ($result->num_rows) {
            if ($resultType == 'array') {
                $resultArray = array();
                while ($component = $result->fetch_array(MYSQLI_ASSOC)) {
                    if ($resultColumn) {
                        $resultArray[] = $component[$resultColumn];
                    } else {
                        $resultArray[] = $component;
                    }
                }

                return $resultArray;
            } else return $result;
        } else
            return null;
    }
}