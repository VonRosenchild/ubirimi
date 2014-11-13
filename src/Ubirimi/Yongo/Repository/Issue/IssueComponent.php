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

class IssueComponent
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