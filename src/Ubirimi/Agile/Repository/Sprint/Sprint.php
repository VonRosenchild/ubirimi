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

namespace Ubirimi\Agile\Repository\Sprint;

use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Yongo\Repository\Issue\Issue;

class Sprint
{
    public function add($boardId, $name, $date, $loggedInUserId) {
        $query = "INSERT INTO agile_board_sprint(agile_board_id, user_created_id, name, date_created) VALUES (?, ?, ?, ?)";
        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("iiss", $boardId, $loggedInUserId, $name, $date);
        $stmt->execute();
    }

    public function getNotStarted($boardId) {
        $query = "select * " .
            "from agile_board_sprint " .
            "where agile_board_id = ? and started_flag = 0 " .
            "order by id asc";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $boardId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getIssuesBySprintId($sprintId, $onlyMyIssuesFlag, $loggedInUserId, $searchText = null) {
        $query = 'select yongo_issue.id, parent_id, nr, issue_priority.name as priority, issue_status.name as status, summary, yongo_issue.description, environment, ' .
            'issue_type.name as type, ' .
            'issue_status.id as status, ' .
            'project.code as project_code, project.name as project_name, yongo_issue.project_id as issue_project_id, issue_type.id as type, ' .
            'issue_type.description as issue_type_description, issue_type.icon_name as issue_type_icon_name, ' .
            'issue_priority.description as issue_priority_description, issue_priority.icon_name as issue_priority_icon_name, issue_priority.color as priority_color, ' .
            'yongo_issue.security_scheme_level_id as security_level ' .
            "from agile_board_sprint_issue " .
            "left join yongo_issue on yongo_issue.id = agile_board_sprint_issue.issue_id " .
            'LEFT JOIN issue_priority on yongo_issue.priority_id = issue_priority.id ' .
            'LEFT JOIN issue_type on yongo_issue.type_id = issue_type.id ' .
            'LEFT JOIN issue_status on yongo_issue.status_id = issue_status.id ' .
            'LEFT JOIN project on yongo_issue.project_id = project.id ' .
            'left join issue_security_scheme_level on issue_security_scheme_level.id = yongo_issue.security_scheme_level_id ' .
            "where agile_board_sprint_issue.agile_board_sprint_id = ? and yongo_issue.parent_id is null ";

        if ($searchText)
            $query .= " and yongo_issue.summary like '%" . $searchText . "%' ";

        if ($onlyMyIssuesFlag)
            $query .= ' and yongo_issue.user_assigned_id = ' . $loggedInUserId . ' ';

        $query .= "order by id asc";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $sprintId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getIssuesBySprintIdWithChildren($sprintId, $onlyMyIssuesFlag, $loggedInUserId) {
        $query = 'select yongo_issue.id, parent_id, nr, issue_priority.name as priority, issue_status.name as status, summary, yongo_issue.description, environment, ' .
            'issue_type.name as type, ' .
            'project.code as project_code, project.name as project_name, yongo_issue.project_id as issue_project_id, issue_type.id as issue_type_id, ' .
            'issue_type.description as issue_type_description, issue_type.icon_name as issue_type_icon_name, ' .
            'issue_priority.description as issue_priority_description, issue_priority.icon_name as issue_priority_icon_name ' .
            "from agile_board_sprint_issue " .
            "left join yongo_issue on yongo_issue.id = agile_board_sprint_issue.issue_id " .
            'LEFT JOIN issue_priority on yongo_issue.priority_id = issue_priority.id ' .
            'LEFT JOIN issue_type on yongo_issue.type_id = issue_type.id ' .
            'LEFT JOIN issue_status on yongo_issue.status_id = issue_status.id ' .
            'LEFT JOIN project on yongo_issue.project_id = project.id ' .
            "where agile_board_sprint_issue.agile_board_sprint_id = ? and yongo_issue.parent_id is not null ";

        if ($onlyMyIssuesFlag)
            $query .= ' and yongo_issue.user_assigned_id = ' . $loggedInUserId . ' ';

        $query .= "group by parent_id ";
        $query .= "order by id asc";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $sprintId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            $resultArray = array();
            while ($issue = $result->fetch_array(MYSQLI_ASSOC)) {
                $resultArray[] = $issue['parent_id'];
            }

            return UbirimiContainer::get()['repository']->get(Issue::class)->getByParameters(array('issue_id' => $resultArray), $loggedInUserId);
        } else
            return null;
    }

    public function getIssuesBySprintIdWithoutChildren($sprintId, $onlyMyIssuesFlag, $loggedInUserId, $parentChildrenIssueIds) {
        $query = 'select yongo_issue.id, parent_id, nr, issue_priority.name as priority, issue_status.name as status, summary, yongo_issue.description, environment, ' .
            'issue_type.name as type_name, issue_type.id as type, ' .
            'issue_status.id as status, ' .
            'project.code as project_code, project.name as project_name, yongo_issue.project_id as issue_project_id, ' .
            'issue_type.description as issue_type_description, issue_type.icon_name as issue_type_icon_name, ' .
            'issue_priority.description as issue_priority_description, issue_priority.icon_name as issue_priority_icon_name, issue_priority.color as priority_color, ' .
            'general_user.id as assignee, general_user.avatar_picture as assignee_avatar_picture, general_user.first_name as ua_first_name, general_user.last_name as ua_last_name ' .
            "from agile_board_sprint_issue " .
            "left join yongo_issue on yongo_issue.id = agile_board_sprint_issue.issue_id " .
            'LEFT JOIN issue_priority on yongo_issue.priority_id = issue_priority.id ' .
            'LEFT JOIN issue_type on yongo_issue.type_id = issue_type.id ' .
            'LEFT JOIN issue_status on yongo_issue.status_id = issue_status.id ' .
            'LEFT JOIN project on yongo_issue.project_id = project.id ' .
            'left join general_user on general_user.id = yongo_issue.user_assigned_id ' .
            "where agile_board_sprint_issue.agile_board_sprint_id = ? and yongo_issue.parent_id is null ";
        if (count($parentChildrenIssueIds))
            $query .= "and yongo_issue.id not in (" . implode(', ', $parentChildrenIssueIds) . ') ';

        if ($onlyMyIssuesFlag)
            $query .= ' and yongo_issue.user_assigned_id = ' . $loggedInUserId . ' ';

        $query .= "order by id asc";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $sprintId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            return $result;
        } else
            return null;
    }

    public function getById($sprintId) {
        $query = "select * " .
            "from agile_board_sprint " .
            "where id = ? " .
            "limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $sprintId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return null;
    }

    public function start($sprintId, $startDate, $endDate, $name) {
        $query = "update agile_board_sprint set name = ?, date_start = ?, date_end = ?, started_flag = 1 where id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("sssi", $name, $startDate, $endDate, $sprintId);
        $stmt->execute();
    }

    public function getStarted($boardId) {
        $query = "select * " .
            "from agile_board_sprint " .
            "where agile_board_id = ? and started_flag = 1 and finished_flag = 0 " .
            "limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $boardId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return null;
    }

    public function getCompletedIssuesCountBySprintId($sprintId, $completeStatuses) {
        $query = "select * " .
            "from agile_board_sprint_issue " .
            "left join yongo_issue on yongo_issue.id = agile_board_sprint_issue.issue_id " .
            "where agile_board_sprint_id = ? " .
            "and yongo_issue.status_id IN (" . implode(', ', $completeStatuses) . ')';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $sprintId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result->num_rows;
        else
            return 0;
    }

    public function getSprintIssuesCount($sprintId) {
        $query = "select * " .
            "from agile_board_sprint_issue " .
            "where agile_board_sprint_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $sprintId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result->num_rows;
        else
            return null;
    }

    public function complete($sprintId) {
        $query = "update agile_board_sprint set finished_flag = 1 where id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $sprintId);
        $stmt->execute();
    }

    public function getNextNotStartedByBoardId($boardId, $sprintId) {
        $query = "select * " .
            "from agile_board_sprint " .
            "where agile_board_id = ? and id != ? " .
            "and started_flag = 0 " .
            "order by id asc " .
            "limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $boardId, $sprintId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return null;
    }

    public function getCompleted($boardId, $sprintId = null) {
        $query = "select * " .
            "from agile_board_sprint " .
            "where agile_board_id = ? and finished_flag = 1 ";
        if ($sprintId)
            $query .= 'and id != ' . $sprintId . ' ';

        $query .= "order by id desc";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $boardId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getByBoardId($boardId, $resultType = null, $column = null) {
        $query = "select * " .
            "from agile_board_sprint " .
            "where agile_board_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $boardId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            if ($resultType == 'array') {
                $resultArray = array();
                while ($sprint = $result->fetch_array(MYSQLI_ASSOC)) {
                    if ($column)
                        $resultArray[] = $sprint[$column];
                    else
                        $resultArray[] = $sprint;
                }
                return $resultArray;
            } else {
                return $result;
            }
        } else {
            return null;
        }
    }

    public function getAllSprintsForClients() {
        $query = "select agile_board_sprint.*, client.company_name, client.company_domain " .
            "from agile_board_sprint " .
            "left join agile_board on agile_board.id = agile_board_sprint.agile_board_id " .
            "left join client on client.id = agile_board.client_id " .
            "order by agile_board_sprint.id desc";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            return $result;
        } else {
            return null;
        }
    }

    public function getLast($boardId) {
        $query = "select * " .
            "from agile_board_sprint " .
            "where agile_board_id = ? " .
            "order by id desc " .
            "limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $boardId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            return $result->fetch_array(MYSQLI_ASSOC);
        } else {
            return null;
        }
    }

    public function deleteById($sprintId) {
        $query = "delete from agile_board_sprint_issue where agile_board_sprint_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $sprintId);
        $stmt->execute();

        $query = "delete from agile_board_sprint where id = ? limit 1";
        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $sprintId);
        $stmt->execute();
    }

    public function getByIssueId($clientId, $issueId) {
        $query = "select agile_board_sprint.id, agile_board_sprint.name " .
            "from agile_board_sprint_issue " .
            "left join agile_board_sprint on agile_board_sprint.id = agile_board_sprint_issue.agile_board_sprint_id " .
            "left join agile_board on agile_board.id = agile_board_sprint.agile_board_id " .
            "where agile_board_sprint_issue.issue_id = ? and agile_board.client_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $issueId, $clientId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function addIssues($sprintId, $issueIdArray, $loggedInUserId) {
        $query = "INSERT INTO agile_board_sprint_issue(agile_board_sprint_id, issue_id) VALUES ";
        for ($i = 0; $i < count($issueIdArray) - 1; $i++) {
            $query .= '(' . $sprintId . ', ' . $issueIdArray[$i] . '), ';
        }

        $query .= '(' . $sprintId . ', ' . $issueIdArray[count($issueIdArray) - 1] . ');';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->execute();

        // also add the children of these issues to the sprint
        $issues = UbirimiContainer::get()['repository']->get(Issue::class)->getByParameters(array('parent_id' => $issueIdArray), $loggedInUserId);

        if ($issues) {
            // get the ids
            $issueIds = array();
            while ($issueChild = $issues->fetch_array(MYSQLI_ASSOC)) {
                $issueIds[] = $issueChild['id'];
            }

            $query = "INSERT INTO agile_board_sprint_issue(agile_board_sprint_id, issue_id) VALUES ";
            for ($i = 0; $i < count($issueIds) - 1; $i++) {
                $query .= '(' . $sprintId . ', ' . $issueIds[$i] . '), ';
            }

            $query .= '(' . $sprintId . ', ' . $issueIds[count($issueIds) - 1] . ');';
            $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
            $stmt->execute();
        }
    }

    public function getLastCompleted($boardId) {
        $query = "select * " .
            "from agile_board_sprint " .
            "where agile_board_id = ? and finished_flag = 1 " .
            "order by id desc " .
            "limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $boardId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return null;
    }

    public function getCompletedUncompletedIssuesBySprintId($sprintId, $doneFlag) {
        $query = 'select yongo_issue.id, nr, summary, yongo_issue.description, environment, ' .
            'issue_priority.name as priority_name, ' .
            'issue_type.name as type_name, issue_type.id as type, ' .
            'issue_status.name as status_name, issue_status.id as status, ' .
            'project.code as project_code, project.name as project_name, yongo_issue.project_id as issue_project_id ' .
            "from agile_board_sprint_issue " .
            "left join yongo_issue on yongo_issue.id = agile_board_sprint_issue.issue_id " .
            'LEFT JOIN issue_priority on yongo_issue.priority_id = issue_priority.id ' .
            'LEFT JOIN issue_type on yongo_issue.type_id = issue_type.id ' .
            'LEFT JOIN issue_status on yongo_issue.status_id = issue_status.id ' .
            'LEFT JOIN project on yongo_issue.project_id = project.id ' .
            "where agile_board_sprint_id = ? and done_flag = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $sprintId, $doneFlag);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getAssigneesBySprintId($sprintId) {
        $query = "select general_user.id, general_user.first_name, general_user.last_name " .
            "from agile_board_sprint_issue " .
            "left join yongo_issue on yongo_issue.id = agile_board_sprint_issue.issue_id " .
            "left join general_user on general_user.id = yongo_issue.user_assigned_id " .
            "where agile_board_sprint_issue.agile_board_sprint_id = ? and general_user.id is not null " .
            "group by general_user.id";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $sprintId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }
}
