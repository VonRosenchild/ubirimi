<?php

namespace Ubirimi\Agile\Repository\Board;

use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Filter;
use Ubirimi\Yongo\Repository\Issue\Settings;


class Board
{
    public $name;
    public $description;
    public $clientId;
    public $filterId;
    public $projects;

    function __construct($clientId, $filterId, $name, $description, $projects ) {
        $this->clientId = $clientId;
        $this->filterId = $filterId;
        $this->name = $name;
        $this->description = $description;
        $this->projects = $projects;

        return $this;
    }

    public function save($userCreatedId, $currentDate) {
        $query = "INSERT INTO agile_board(client_id, filter_id, name, description, swimlane_strategy, user_created_id, date_created) VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $defaultSwimlaneStrategy = 'story';
        $stmt->bind_param("iisssis", $this->clientId, $this->filterId, $this->name, $this->description, $defaultSwimlaneStrategy, $userCreatedId, $currentDate);
        $stmt->execute();

        $boardId = UbirimiContainer::get()['db.connection']->insert_id;

        for ($i = 0; $i < count($this->projects); $i++) {
            $query = "INSERT INTO agile_board_project(agile_board_id, project_id) VALUES (?, ?)";

            $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

            $stmt->bind_param("ii", $boardId, $this->projects[$i]);
            $stmt->execute();
        }

        return $boardId;
    }

    public function getByClientId($clientId, $resultType = null) {
        $query = "select agile_board.client_id, agile_board.id, agile_board.filter_id, agile_board.name, agile_board.description, agile_board.swimlane_strategy, " .
                 "agile_board.user_created_id, agile_board.date_created, user.first_name, user.last_name, " .
                 "filter.name as filter_name, filter.id as filter_id, filter.definition as filter_definition " .
            "from agile_board " .
            "left join user on user.id = agile_board.user_created_id " .
            "left join filter on filter.id = agile_board.filter_id " .
            "where agile_board.client_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $clientId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            if ($resultType == 'array') {
                $resultArray = array();
                while ($board = $result->fetch_array(MYSQLI_ASSOC)) {
                    $resultArray[] = $board;
                }

                return $resultArray;
            } else
                return $result;
        } else
            return null;
    }

    public function getById($boardId) {
        $query = "select agile_board.id, agile_board.client_id, agile_board.name, agile_board.description, agile_board.user_created_id, agile_board.swimlane_strategy, " .
                 "filter.id as filter_id, filter.name as filter_name, filter.description as filter_description, " .
                 "user.first_name, user.last_name " .
            "from agile_board " .
            "left join filter on filter.id = agile_board.filter_id " .
            "left join user on user.id = agile_board.user_created_id " .
            "where agile_board.id = ? " .
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

    public function getProjects($boardId, $resultType = null) {
        $query = "select project.id, project.name " .
            "from agile_board_project " .
            "left join project on project.id = agile_board_project.project_id " .
            "where agile_board_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $boardId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            if ($resultType == 'array') {
                $resultArray = array();
                while ($prj = $result->fetch_array(MYSQLI_ASSOC)) {
                    $resultArray[] = $prj;
                }

                return $resultArray;
            } else return $result;

        } else
            return null;
    }

    public function addStatusToColumn($columnId, $StatusId) {
        $query = "INSERT INTO agile_board_column_status(agile_board_column_id, issue_status_id) VALUES (?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("ii", $columnId, $StatusId);
        $stmt->execute();
    }

    public function addDefaultColumnData($clientId, $boardId) {
        // add To Do column
        $query = "INSERT INTO agile_board_column(agile_board_id, position, name) VALUES (?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $columnName = 'To Do';
        $position = 1;
        $stmt->bind_param("iis", $boardId, $position, $columnName);
        $stmt->execute();
        $columnId = UbirimiContainer::get()['db.connection']->insert_id;
        $openStatusData = Settings::getByName($clientId, 'status', 'Open');
        $reopenedStatusData = Settings::getByName($clientId, 'status', 'Reopened');
        $this->getRepository('agile.board.board')->addStatusToColumn($columnId, $openStatusData['id']);
        $this->getRepository('agile.board.board')->addStatusToColumn($columnId, $reopenedStatusData['id']);

        // add In Progress column
        $query = "INSERT INTO agile_board_column(agile_board_id, position, name) VALUES (?, ?, ?)";
        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $columnName = 'In Progress';
        $position = 2;
        $stmt->bind_param("iis", $boardId, $position, $columnName);
        $stmt->execute();
        $columnId = UbirimiContainer::get()['db.connection']->insert_id;
        $inProgressStatusData = Settings::getByName($clientId, 'status', 'In Progress');

        $this->getRepository('agile.board.board')->addStatusToColumn($columnId, $inProgressStatusData['id']);

        // add Done column
        $query = "INSERT INTO agile_board_column(agile_board_id, position, name) VALUES (?, ?, ?)";
        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $columnName = 'Done';
        $position = 3;
        $stmt->bind_param("iis", $boardId, $position, $columnName);
        $stmt->execute();
        $columnId = UbirimiContainer::get()['db.connection']->insert_id;
        $resolvedStatusData = Settings::getByName($clientId, 'status', 'Resolved');
        $closedStatusData = Settings::getByName($clientId, 'status', 'Closed');

        $this->getRepository('agile.board.board')->addStatusToColumn($columnId, $resolvedStatusData['id']);
        $this->getRepository('agile.board.board')->addStatusToColumn($columnId, $closedStatusData['id']);
    }

    public function getColumns($boardId, $resultType = null) {
        $query = "select agile_board_column.* " .
            "from agile_board_column " .
            "where agile_board_column.agile_board_id = ? " .
            "order by position";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $boardId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            if ($resultType == 'array') {
                $resultArray = array();
                while ($column = $result->fetch_array(MYSQLI_ASSOC)) {
                    $resultArray[] = $column;
                }

                return $resultArray;
            } else return $result;

        } else
            return null;
    }

    public function getColumnStatuses($columnId, $resultType = null, $column = null) {
        $query = "select issue_status.id, issue_status.name " .
            "from agile_board_column_status " .
            "left join issue_status on issue_status.id = agile_board_column_status.issue_status_id " .
            "where agile_board_column_status.agile_board_column_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $columnId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            if ($resultType == 'array') {
                $resultArray = array();
                while ($status = $result->fetch_array(MYSQLI_ASSOC)) {
                    if ($column)
                        $resultArray[] = $status[$column];
                    else
                        $resultArray[] = $status;
                }

                return $resultArray;
            } else return $result;

        } else
            return null;
    }

    public function deleteStatusFromColumn($boardId, $StatusId) {
        $columns = $this->getRepository('agile.board.board')->getColumns($boardId, 'array');
        $columnsIds = array();
        for ($i = 0; $i < count($columns); $i++) {
            $columnsIds[] = $columns[$i]['id'];
        }

        $query = "delete from agile_board_column_status where issue_status_id = ? and agile_board_column_id IN (" . implode(', ', $columnsIds) . ')';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $StatusId);
        $stmt->execute();
    }

    public function getUnmappedStatuses($clientId, $boardId, $resultType = null) {
        $clientStatuses = Settings::getAllIssueSettings('status', $clientId, 'array');

        $query = "select issue_status.id, issue_status.name " .
            "from agile_board " .
            "left join agile_board_column on agile_board_column.agile_board_id = agile_board.id " .
            "left join agile_board_column_status on agile_board_column_status.agile_board_column_id = agile_board_column.id " .
            "left join issue_status on issue_status.id = agile_board_column_status.issue_status_id " .
            "where agile_board.id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $boardId);
        $stmt->execute();
        $result = $stmt->get_result();

        $resultArray = array();

        if ($result->num_rows) {
            for ($i = 0; $i < count($clientStatuses); $i++) {
                $found = false;
                while ($status = $result->fetch_array(MYSQLI_ASSOC)) {
                    if ($clientStatuses[$i]['id'] == $status['id']) {
                        $found = true;
                        break;
                    }
                }
                if (!$found)
                    $resultArray[] = $clientStatuses[$i];
                $result->data_seek(0);
            }
        }

        return $resultArray;
    }

    public function addColumn($boardId, $name, $description) {
        $query = "INSERT INTO agile_board_column(agile_board_id, name) VALUES (?, ?)";
        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("is", $boardId, $name);
        $stmt->execute();
    }

    public function deleteColumn($columnId) {
        $query = "delete from agile_board_column_status where agile_board_column_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $columnId);
        $stmt->execute();

        $query = "delete from agile_board_column where id = ? limit 1";
        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $columnId);
        $stmt->execute();
    }

    public function getLast5BoardsByClientId($clientId) {
        $query = "select * " .
            "from agile_board " .
            "where client_id = ? " .
            "order by id desc";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $clientId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getBacklogIssues($clientId, $boardData, $onlyMyIssuesFlag, $loggedInUserId, $searchText, $completeStatuses) {
        $filterId = $boardData['filter_id'];

        $filterData = Filter::getById($filterId);
        $definition = $filterData['definition'];
        $definitionArray = explode('&', $definition);
        $searchParameters = array();
        for ($i = 0; $i < count($definitionArray); $i++) {
            $keyValueArray = explode('=', $definitionArray[$i]);
            if ($keyValueArray[0] != 'search_query') {
                $searchParameters[$keyValueArray[0]] = explode('|', $keyValueArray[1]);
            } else {
                $searchParameters['search_query'] = $keyValueArray[1];
            }
        }

        $searchParameters['client_id'] = $clientId;
        $searchParameters['backlog'] = true;
        if ($onlyMyIssuesFlag)
            $searchParameters['assignee'] = $loggedInUserId;

        if ($searchText) {
            $searchParameters['search_query'] = $searchText;
            $searchParameters['summary_flag'] = 1;
        }

        $searchParameters['not_status'] = $completeStatuses;

        return UbirimiContainer::getRepository('yongo.issue.issue')->getByParameters($searchParameters, $loggedInUserId);
    }

    public function deleteIssuesFromSprints($issueIdArray) {
        $query = "delete from agile_board_sprint_issue where issue_id IN (" . implode(", ", $issueIdArray) . ')';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->execute();
    }

    public function getIssuesBySprintAndStatusIdAndParentId($sprintId, $parentId = null, $statuses, $onlyMyIssuesFlag, $loggedInUserId) {
        $query = 'select yongo_issue.id, nr, yongo_issue.parent_id, issue_priority.name as priority_name, issue_status.name as status_name, issue_status.id as status, summary, yongo_issue.description, environment, ' .
            'issue_type.name as type, ' .
            'project.code as project_code, project.name as project_name, yongo_issue.project_id as issue_project_id, ' .
            'issue_type.id as type, issue_type.description as issue_type_description, issue_type.icon_name as issue_type_icon_name, ' .
            'issue_priority.description as issue_priority_description, issue_priority.icon_name as issue_priority_icon_name, issue_priority.color as priority_color ' .
            "from agile_board_sprint_issue " .
            "left join yongo_issue on yongo_issue.id = agile_board_sprint_issue.issue_id " .
            'LEFT JOIN issue_priority on yongo_issue.priority_id = issue_priority.id ' .
            'LEFT JOIN issue_type on yongo_issue.type_id = issue_type.id ' .
            'LEFT JOIN issue_status on yongo_issue.status_id = issue_status.id ' .
            'LEFT JOIN project on yongo_issue.project_id = project.id ' .
            "where agile_board_sprint_issue.agile_board_sprint_id = ? " .
            "and yongo_issue.status_id IN (" . implode(", ", $statuses) . ") ";
        if ($onlyMyIssuesFlag)
            $query .= 'and yongo_issue.user_assigned_id = ' . $loggedInUserId . ' ';
        if ($parentId)
            $query .= 'and yongo_issue.parent_id = ' . $parentId . ' ';
        else
            $query .= 'and yongo_issue.parent_id is null ';
        $query .= "order by id desc";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $sprintId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getLastColumn($boardId) {
        $query = "select * " .
            "from agile_board_column " .
            "where agile_board_id = ? " .
            "order by position desc " .
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

    public function transferNotDoneIssues($boardId, $sprintId, $completeStatuses) {
        $nextSprint = AgileSprint::getNextNotStartedByBoardId($boardId, $sprintId);

        // set as done the completed issues
        $query = "select * " .
            "from agile_board_sprint_issue " .
            "left join yongo_issue on yongo_issue.id = agile_board_sprint_issue.issue_id " .
            "where agile_board_sprint_id = ? " .
            "and yongo_issue.status_id IN (" . implode(', ', $completeStatuses) . ')';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $sprintId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows) {
            $issueIdArray = array();
            while ($issue = $result->fetch_array(MYSQLI_ASSOC)) {
                $issueIdArray[] = $issue['issue_id'];
            }
            $queryUpdate = 'update agile_board_sprint_issue set done_flag = 1 where agile_board_sprint_id = ? and issue_id IN (' . implode(', ', $issueIdArray) . ')';

            $stmtUpdate = UbirimiContainer::get()['db.connection']->prepare($queryUpdate);
            $stmtUpdate->bind_param("i", $sprintId);
            $stmtUpdate->execute();
        }

        // transfer the not done issues to the next sprint
        $query = "select * " .
            "from agile_board_sprint_issue " .
            "left join yongo_issue on yongo_issue.id = agile_board_sprint_issue.issue_id " .
            "where agile_board_sprint_id = ? " .
            "and done_flag = 0";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $sprintId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            if ($nextSprint) {

                $issueIdArray = array();
                while ($issue = $result->fetch_array(MYSQLI_ASSOC)) {
                    $issueIdArray[] = $issue['issue_id'];
                }
                $queryTransfer = 'insert into agile_board_sprint_issue(agile_board_sprint_id, issue_id) values ';
                $queryTransferPart = array();
                for ($i = 0; $i < count($issueIdArray); $i++) {
                    $queryTransferPart[] = '(' . $nextSprint['id'] . ', ' . $issueIdArray[$i] . ')';
                }
                $queryTransfer .= implode(', ', $queryTransferPart);
                $stmtTransfer = UbirimiContainer::get()['db.connection']->prepare($queryTransfer);
                $stmtTransfer->execute();
            }
        }
    }

    public function deleteByProjectId($projectId) {
        $query = "delete from agile_board_project where project_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $projectId);
        $stmt->execute();
    }

    public function deleteById($boardId) {
        $boardColumnsArray = $this->getRepository('agile.board.board')->getColumns($boardId, 'array');
        $boardColumnsIds = Util::array_column($boardColumnsArray, 'id');

        $query = "delete from agile_board_column_status where agile_board_column_id IN (" . implode(', ', $boardColumnsIds) . ')';
        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->execute();

        $query = "delete from agile_board_column where agile_board_id = ?";
        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $boardId);
        $stmt->execute();

        $query = "delete from agile_board_project where agile_board_id = ?";
        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $boardId);
        $stmt->execute();

        $sprintIdsArray = AgileSprint::getByBoardId($boardId, 'array', 'id');
        for ($i = 0; $i < count($sprintIdsArray); $i++) {
            $query = "delete from agile_board_sprint_issue where agile_board_sprint_id = ?";
            $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
            $stmt->bind_param("i", $sprintIdsArray[$i]);
            $stmt->execute();
        }

        $query = "delete from agile_board_sprint where agile_board_id = ?";
        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $boardId);
        $stmt->execute();

        $query = "delete from agile_board where id = ? limit 1";
        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $boardId);
        $stmt->execute();
    }

    public function updateColumnOrder($newOrder) {
        for ($i = 0; $i < count($newOrder); $i++) {
            $query = "update agile_board_column set position = ? where id = ? limit 1";

            $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
            $position = $i + 1;
            $stmt->bind_param("ii", $position, $newOrder[$i]);
            $stmt->execute();
        }
    }

    public function renderIssues($issues, $columns, $indexSection, $swimlaneStrategy = null) { ?>

    <?php }

    public function updateSwimlaneStrategy($boardId, $strategy) {
        $query = "update agile_board set swimlane_strategy = ? where id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("si", $strategy, $boardId);
        $stmt->execute();
    }

    public function updateMetadata($clientId, $boardId, $name, $description, $date) {
        $query = "update agile_board set name = ?, description = ?, date_updated = ? where client_id = ? and id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("sssii", $name, $description, $date, $clientId, $boardId);
        $stmt->execute();
    }

    public function getByFilterId($filterId) {
        $query = "select * " .
            "from agile_board " .
            "where filter_id = ? " .
            "order by id desc";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $filterId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {

            return $result;
        } else {
            return null;
        }
    }

    public function getAll($filters = null) {
        $query = "select * " .
            "from agile_board ";

        if (empty($filters['sort_by'])) {
            $query .= ' order by agile_board.id';
        } else {
            $query .= " order by " . $filters['sort_by'] . ' ' . $filters['sort_order'];
        }

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            return $result;
        } else {
            return null;
        }
    }
}
