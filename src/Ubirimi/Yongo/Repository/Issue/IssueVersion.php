<?php

    namespace Ubirimi\Yongo\Repository\Issue;

    use Ubirimi\Container\UbirimiContainer;

    class IssueVersion {

        public static function deleteByIssueIdAndFlag($issueId, $flag) {
            $query = 'DELETE FROM issue_version WHERE issue_id = ? and affected_targeted_flag = ?';

            if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
                $stmt->bind_param("ii", $issueId, $flag);
                $stmt->execute();
            }
        }

        public static function deleteByIssueId($issueId) {
            $query = 'DELETE FROM issue_version WHERE issue_id = ?';

            if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
                $stmt->bind_param("i", $issueId);
                $stmt->execute();
            }
        }

        public static function getByIssueIdAndProjectId($issueId, $projectId, $versionFlag, $resultType = null, $resultColumn = null) {
            $query = 'SELECT issue_version.id, project_version.name, project_version_id ' .
                'FROM issue_version ' .
                'LEFT JOIN project_version on issue_version.project_version_id = project_version.id ' .
                'WHERE issue_id = ? and project_version.project_id = ? ' .
                'AND affected_targeted_flag = ?';

            if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
                $stmt->bind_param("iii", $issueId, $projectId, $versionFlag);
                $stmt->execute();

                $result = $stmt->get_result();
                if ($result->num_rows) {
                    if ($resultType == 'array') {
                        $resultArray = array();
                        while ($version = $result->fetch_array(MYSQLI_ASSOC)) {
                            if ($resultColumn)
                                $resultArray[] = $version[$resultColumn];
                            else
                                $resultArray[] = $version;
                        }

                        return $resultArray;
                    }

                    return $result;
                } else
                    return null;
            }
        }
    }