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

class IssueVersion
{
    public function deleteByIssueIdAndFlag($issueId, $flag) {
        $query = 'DELETE FROM issue_version WHERE issue_id = ? and affected_targeted_flag = ?';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $issueId, $flag);
        $stmt->execute();
    }

    public function deleteByIssueId($issueId) {
        $query = 'DELETE FROM issue_version WHERE issue_id = ?';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $issueId);
        $stmt->execute();
    }

    public function getByIssueIdAndProjectId($issueId, $projectId, $versionFlag, $resultType = null, $resultColumn = null) {
        $query = 'SELECT issue_version.id, project_version.name, project_version_id ' .
            'FROM issue_version ' .
            'LEFT JOIN project_version on issue_version.project_version_id = project_version.id ' .
            'WHERE issue_id = ? and project_version.project_id = ? ' .
            'AND affected_targeted_flag = ?';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
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