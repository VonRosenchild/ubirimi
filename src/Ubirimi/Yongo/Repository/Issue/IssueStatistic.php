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

class IssueStatistic
{
    public function getUnresolvedIssuesByProjectForUser($userId) {
        $q = 'select count(yongo_issue.id) as total, project.name, project.id as project_id ' .
            'from yongo_issue ' .
            'left join project on yongo_issue.project_id = project.id ' .
            'where yongo_issue.user_assigned_id = ? ' .
            'and yongo_issue.resolution_id is null ' .
            'and project.name is not null ' .
            'group by yongo_issue.project_id';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($q);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getComponentOrVersionStatsUnresolvedBySetting($projectId, $setting, $comp_version_id, $type) {
        $query = 'select count(yongo_issue.id) as count, ';
        if ($setting != 'assignee') {
            $query .= 'issue_' . $setting . '.name, yongo_issue.' . $setting . '_id as setting_id ';
        }
        else
            $query .= 'CONCAT(user.first_name, " ", general_user.last_name) as name, yongo_issue.user_assigned_id ';

        $query .= 'from yongo_issue ' .
            'left join issue_' . $type . ' on yongo_issue.id = issue_' . $type . '.issue_id ';

        $group_by = '';
        switch ($setting) {
            case 'priority':
                $query .= 'left join issue_priority on yongo_issue.priority_id = issue_priority.id ';
                $group_by = 'group by yongo_issue.priority_id';
                break;
            case 'type':
                $query .= 'left join issue_type on yongo_issue.type_id = issue_type.id ';
                $group_by = 'group by yongo_issue.type_id';
                break;
            case 'status':
                $query .= 'left join issue_status on yongo_issue.status_id = issue_status.id ';
                $group_by = 'group by yongo_issue.status_id';
                break;
            case 'assignee':
                $query .= 'left join general_user on yongo_issue.user_assigned_id = general_user.id ';
                $group_by = 'group by yongo_issue.user_assigned_id';
                break;
        }
        $query .= 'where issue_' . $type . '.project_' . $type . '_id = ? ';
        if ($type == 'version')
            $query .= 'and issue_version.affected_targeted_flag = ' . Issue::ISSUE_FIX_VERSION_FLAG  . ' ';
        $query .= 'and yongo_issue.resolution_id is null ';
        $query .= 'and yongo_issue.project_id = ? ';

        $query .= $group_by;

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $comp_version_id, $projectId);
        $stmt->execute();

        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getComponentORVersionCountUnresolved($comp_version_id, $type) {
        $query = 'select count(yongo_issue.id) as count ' .
            'from yongo_issue ' .
            'left join issue_' . $type . ' on yongo_issue.id = issue_' . $type . '.issue_id ' .
            'where issue_' . $type . '.project_' . $type . '_id = ? ' .
            'and yongo_issue.resolution_id is null';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $comp_version_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            $row_result = $result->fetch_array(MYSQLI_ASSOC);
            return $row_result['count'];
        }
        else
            return null;
    }
}
