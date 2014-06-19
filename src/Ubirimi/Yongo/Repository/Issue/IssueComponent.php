<?php

    namespace Ubirimi\Yongo\Repository\Issue;

    use Ubirimi\Container\UbirimiContainer;

    class IssueComponent {

        public static function deleteByIssueId($issueId) {
            $query = 'DELETE FROM issue_component WHERE issue_id = ?';

            if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
                $stmt->bind_param("i", $issueId);
                $stmt->execute();
            }
        }

        public static function getByIssueId($issueId) {
            $query = 'SELECT issue_component.id, project_component.name, project_component_id, parent_id ' .
                'FROM issue_component ' .
                'LEFT JOIN project_component on issue_component.project_component_id = project_component.id ' .
                'WHERE issue_id = ? ' .
                'order by id asc';

            if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
                $stmt->bind_param("i", $issueId);
                $stmt->execute();

                $result = $stmt->get_result();
                if ($result->num_rows)
                    return $result;
                else
                    return null;
            }
        }
    }