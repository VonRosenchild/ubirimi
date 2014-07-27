<?php

namespace Ubirimi\Agile\Repository;

use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\User\User;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Field\Field;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Issue\IssueFilter;
use Ubirimi\Yongo\Repository\Issue\IssueSettings;
use Ubirimi\Yongo\Repository\Project\Project;
use Ubirimi\Yongo\Repository\Workflow\Workflow;

class AgileBoard
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

    public static function getByClientId($clientId, $resultType = null) {
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

    public static function getById($boardId) {
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

    public static function getProjects($boardId, $resultType = null) {
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

    public static function addStatusToColumn($columnId, $StatusId) {
        $query = "INSERT INTO agile_board_column_status(agile_board_column_id, issue_status_id) VALUES (?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("ii", $columnId, $StatusId);
        $stmt->execute();
    }

    public static function addDefaultColumnData($clientId, $boardId) {
        // add To Do column
        $query = "INSERT INTO agile_board_column(agile_board_id, position, name) VALUES (?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $columnName = 'To Do';
        $position = 1;
        $stmt->bind_param("iis", $boardId, $position, $columnName);
        $stmt->execute();
        $columnId = UbirimiContainer::get()['db.connection']->insert_id;
        $openStatusData = IssueSettings::getByName($clientId, 'status', 'Open');
        $reopenedStatusData = IssueSettings::getByName($clientId, 'status', 'Reopened');
        AgileBoard::addStatusToColumn($columnId, $openStatusData['id']);
        AgileBoard::addStatusToColumn($columnId, $reopenedStatusData['id']);

        // add In Progress column
        $query = "INSERT INTO agile_board_column(agile_board_id, position, name) VALUES (?, ?, ?)";
        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $columnName = 'In Progress';
        $position = 2;
        $stmt->bind_param("iis", $boardId, $position, $columnName);
        $stmt->execute();
        $columnId = UbirimiContainer::get()['db.connection']->insert_id;
        $inProgressStatusData = IssueSettings::getByName($clientId, 'status', 'In Progress');

        AgileBoard::addStatusToColumn($columnId, $inProgressStatusData['id']);

        // add Done column
        $query = "INSERT INTO agile_board_column(agile_board_id, position, name) VALUES (?, ?, ?)";
        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $columnName = 'Done';
        $position = 3;
        $stmt->bind_param("iis", $boardId, $position, $columnName);
        $stmt->execute();
        $columnId = UbirimiContainer::get()['db.connection']->insert_id;
        $resolvedStatusData = IssueSettings::getByName($clientId, 'status', 'Resolved');
        $closedStatusData = IssueSettings::getByName($clientId, 'status', 'Closed');

        AgileBoard::addStatusToColumn($columnId, $resolvedStatusData['id']);
        AgileBoard::addStatusToColumn($columnId, $closedStatusData['id']);
    }

    public static function getColumns($boardId, $resultType = null) {
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

    public static function getColumnStatuses($columnId, $resultType = null, $column = null) {
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

    public static function deleteStatusFromColumn($boardId, $StatusId) {
        $columns = AgileBoard::getColumns($boardId, 'array');
        $columnsIds = array();
        for ($i = 0; $i < count($columns); $i++) {
            $columnsIds[] = $columns[$i]['id'];
        }

        $query = "delete from agile_board_column_status where issue_status_id = ? and agile_board_column_id IN (" . implode(', ', $columnsIds) . ')';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $StatusId);
        $stmt->execute();
    }

    public static function getUnmappedStatuses($clientId, $boardId, $resultType = null) {
        $clientStatuses = IssueSettings::getAllIssueSettings('status', $clientId, 'array');

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

    public static function addColumn($boardId, $name, $description) {
        $query = "INSERT INTO agile_board_column(agile_board_id, name) VALUES (?, ?)";
        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("is", $boardId, $name);
        $stmt->execute();
    }

    public static function deleteColumn($columnId) {
        $query = "delete from agile_board_column_status where agile_board_column_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $columnId);
        $stmt->execute();

        $query = "delete from agile_board_column where id = ? limit 1";
        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $columnId);
        $stmt->execute();
    }

    public static function getLast5BoardsByClientId($clientId) {
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

    public static function getBacklogIssues($clientId, $boardData, $onlyMyIssuesFlag, $loggedInUserId, $searchText, $completeStatuses) {
        $filterId = $boardData['filter_id'];

        $filterData = IssueFilter::getById($filterId);
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

        return Issue::getByParameters($searchParameters, $loggedInUserId);
    }

    public static function deleteIssuesFromSprints($issueIdArray) {
        $query = "delete from agile_board_sprint_issue where issue_id IN (" . implode(", ", $issueIdArray) . ')';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->execute();
    }

    public static function getIssuesBySprintAndStatusIdAndParentId($sprintId, $parentId = null, $statuses, $onlyMyIssuesFlag, $loggedInUserId) {
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

    public static function getLastColumn($boardId) {
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

    public static function transferNotDoneIssues($boardId, $sprintId, $completeStatuses) {
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

    public static function deleteByProjectId($projectId) {
        $query = "delete from agile_board_project where project_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $projectId);
        $stmt->execute();
    }

    public static function deleteById($boardId) {
        $boardColumnsArray = AgileBoard::getColumns($boardId, 'array');
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

    public static function updateColumnOrder($newOrder) {
        for ($i = 0; $i < count($newOrder); $i++) {
            $query = "update agile_board_column set position = ? where id = ? limit 1";

            $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
            $position = $i + 1;
            $stmt->bind_param("ii", $position, $newOrder[$i]);
            $stmt->execute();
        }
    }

    public static function renderIssues($issues, $columns, $indexSection, $swimlaneStrategy = null) { ?>
        <tr id="agile_content_columns">
            <?php for ($i = 0; $i < count($columns); $i++): ?>
                <?php
                    if ($swimlaneStrategy == 'assignee') {
                        $lastParentId = -1;
                        $lastIssueId = -1;
                    }
                ?>
                <td width="<?php echo (100 / count($columns)) ?>%" id="column_data_<?php echo $columns[$i]['id'] . '_' . $indexSection ?>" valign="top" class="droppableAgileColumn_<?php echo $indexSection ?> pageContentSmall">
                    <?php
                        $statuses = AgileBoard::getColumnStatuses($columns[$i]['id'], 'array');
                    ?>
                    <div style="display: none; position: absolute; margin: 4px;" id="statuses_for_column_<?php echo $columns[$i]['id'] . '_' . $indexSection ?>">
                        <?php for ($j = 0; $j < count($statuses); $j++): ?>
                            <div id="status_for_column_<?php echo $columns[$i]['id'] . '_' . $statuses[$j]['id'] . '_' . $indexSection ?>" class="status_for_column_<?php echo $indexSection . '_' . $columns[$i]['id']; ?>" style="border: 4px dashed #b3b3b3; border-radius: 10px" align="center" class="headerPageText"><?php echo $statuses[$j]['name'] ?></div>
                        <?php endfor ?>
                    </div>
                    <?php if ($issues): ?>
                        <?php $issues->data_seek(0); ?>
                        <?php while ($issue = $issues->fetch_array(MYSQLI_ASSOC)): ?>
                            <?php
                                if (!in_array($issue['status'], Util::array_column($statuses, 'id')))
                                    continue;
                                $workflowUsed = Project::getWorkflowUsedForType($issue['issue_project_id'], $issue['type']);
                                $stepWorkflowFrom = Workflow::getStepByWorkflowIdAndStatusId($workflowUsed['id'], $issue[Field::FIELD_STATUS_CODE]);
                                $parentId = -1;
                                if ($issue['parent_id'])
                                    $parentId = $issue['parent_id'];
                            ?>
                            <div style="width: 100%" id="issue_in_column_<?php echo $columns[$i]['id'] . '_' . $issue['id'] . '_' . $issue['issue_project_id'] . '_' . $stepWorkflowFrom['id'] . '_' . $workflowUsed['id'] . '_' . $indexSection . '_' . $parentId; ?>" class="draggableIssueAgileBasic draggableIssueAgile_<?php echo $indexSection ?>">
                                <table width="100%" cellspacing="0px" cellpadding="0px" border="0px">
                                    <?php if ($swimlaneStrategy == 'assignee'): ?>
                                        <?php if ($lastParentId != $issue['parent_id'] && $lastIssueId != $issue['parent_id']): ?>
                                            <?php if (($issue['parent_status_id'] && $issue['parent_status_id'] != $issue['status']) || ($issue['parent_assignee'] == null && $issue['assignee'] != null && $issue['parent_id']) || ($issue['parent_assignee'] && $issue['assignee'] != $issue['parent_assignee'])): ?>
                                                <tr>
                                                    <td bgcolor="#DDDDDD" colspan="4"><?php echo $issue['parent_project_code'] . '-' . $issue['parent_nr'] . ' ' . $issue['parent_summary'] ?></td>
                                                </tr>
                                            <?php endif ?>
                                        <?php endif ?>
                                    <?php endif ?>
                                    <tr>
                                        <?php if ($swimlaneStrategy == 'assignee'): ?>
                                            <?php if ($issue['parent_id']): ?>
                                                <td width="10px"></td>
                                            <?php endif ?>
                                        <?php endif ?>
                                        <td width="10px" bgcolor="<?php echo $issue['priority_color'] ?>"></td>
                                        <td>
                                            <div style="padding-left: 4px">
                                                <div>
                                                    <img src="/yongo/img/issue_type/<?php echo $issue['issue_type_icon_name'] ?>" height="16px" alt="">
                                                    <a href="#" id="agile_issue_<?php echo $issue['id'] ?>"><?php echo $issue['project_code'] . ' ' . $issue['nr'] ?></a>
                                                </div>
                                                <div><img src="/yongo/img/issue_priority/<?php echo $issue['issue_priority_icon_name'] ?>" height="16px" alt=""> <?php echo $issue['summary'] ?></div>
                                            </div>
                                        </td>
                                        <td align="center" width="40px">
                                            <img src="<?php
                                                echo User::getUserAvatarPicture(array('id' => $issue['assignee'], 'avatar_picture' => $issue['assignee_avatar_picture']), 'small') ?>" title="<?php echo $issue['ua_first_name'] . ' ' . $issue['ua_last_name'] ?>" height="33px" style="vertical-align: middle;" />
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <?php
                                if ($swimlaneStrategy == 'assignee') {
                                    $lastParentId = $issue['parent_id'];
                                    $lastIssueId = $issue['id'];
                                }
                            ?>
                        <?php endwhile ?>
                    <?php endif ?>
                    <br />
                    <br />
                    <br />
                    <br />
                </td>
                <?php if ($i != (count($columns) - 1)): ?>
                    <td>
                        <div>&nbsp;&nbsp;</div>
                    </td>
                <?php endif ?>
            <?php endfor ?>
        </tr>
    <?php }

    public static function updateSwimlaneStrategy($boardId, $strategy) {
        $query = "update agile_board set swimlane_strategy = ? where id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("si", $strategy, $boardId);
        $stmt->execute();
    }

    public static function updateMetadata($clientId, $boardId, $name, $description, $date) {
        $query = "update agile_board set name = ?, description = ?, date_updated = ? where client_id = ? and id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("sssii", $name, $description, $date, $clientId, $boardId);
        $stmt->execute();
    }

    public static function getByFilterId($filterId) {
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

    public static function getAll($filters = null) {
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
