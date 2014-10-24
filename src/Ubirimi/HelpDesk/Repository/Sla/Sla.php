<?php

namespace Ubirimi\HelpDesk\Repository\Sla;

use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Yongo\Repository\Field\Field;
use Ubirimi\Yongo\Repository\Issue\History;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Issue\IssueComment;
use Ubirimi\Yongo\Repository\Issue\IssueSettings;

class Sla
{
    const CONDITION_CREATE_ISSUE = 'issue_created';
    const CONDITION_RESOLUTION_SET = 'resolution_set';

    public function getByProjectId($projectId, $resultType = null, $order = null) {
        $query = 'SELECT * from help_sla where project_id = ?';

        if ($order) {
            $query .= ' order by ' . $order;
        } else {
            $query .= ' order by id desc';
        }

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("i", $projectId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            if ($resultType == 'array') {
                $resultArray = array();
                while ($data = $result->fetch_array(MYSQLI_ASSOC)) {
                    $resultArray[] = $data;
                }
                return $resultArray;
            } else {
                return $result;
            }
        } else
            return null;
    }

    public function getByProjectIds($projectIds) {
        $query = 'SELECT * from help_sla where project_id IN (' . implode(', ', $projectIds) . ') order by id desc';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getById($Id) {
        $query = 'SELECT * from help_sla where id = ? limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("i", $Id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return null;
    }

    public function getByName($name, $projectId, $slaId = null) {
        $query = 'select id, name from help_sla where project_id = ? and LOWER(name) = LOWER(?) ';
        if ($slaId) {
            $query .= 'and id != ?';
        }

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        if ($slaId)
            $stmt->bind_param("isi", $projectId, $name, $slaId);
        else
            $stmt->bind_param("is", $projectId, $name);

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result;
        else
            return false;
    }

    public function save($projectId, $name, $description, $startCondition, $stopCondition, $date) {
        $query = "INSERT INTO help_sla(project_id, name, description, start_condition, stop_condition, date_created) VALUES " .
            "(?, ?, ?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("isssss", $projectId, $name, $description, $startCondition, $stopCondition, $date);
        $stmt->execute();

        return UbirimiContainer::get()['db.connection']->insert_id;
    }

    public function addGoal($slaId, $SLACalendarId, $definition, $definitionSQL, $value) {
        $query = "INSERT INTO help_sla_goal(help_sla_id, help_sla_calendar_id, definition, definition_sql, value) VALUES " .
                 "(?, ?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("iisss", $slaId, $SLACalendarId, $definition, $definitionSQL, $value);
        $stmt->execute();

        return UbirimiContainer::get()['db.connection']->insert_id;
    }

    public function getGoals($slaId) {
        $query = 'select help_sla_goal.id, help_sla_goal.help_sla_id, help_sla_goal.help_sla_calendar_id, ' .
                 'help_sla_goal.definition, help_sla_goal.definition_sql, help_sla_goal.value, ' .
                 'help_sla_calendar.name as calendar_name ' .
                 'from help_sla_goal ' .
                 'left join help_sla_calendar on help_sla_calendar.id = help_sla_goal.help_sla_calendar_id ' .
                 'where help_sla_id = ?';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $slaId);

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result;
        else
            return false;
    }

    public function deleteById($Id) {
        $query = "delete from help_sla_goal where help_sla_id = ?";
        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $Id);
        $stmt->execute();
        $stmt->close();

        $query = "delete from help_sla where id = ? limit 1";
        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $Id);
        $stmt->execute();
        $stmt->close();

        $query = "delete from yongo_issue_sla where help_sla_id = ?";
        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $Id);
        $stmt->execute();
        $stmt->close();

        // remove also from the columns of users for displaying issues
        $query = "update user set issues_display_columns = REPLACE(issues_display_columns, '#sla_" . $Id . "', '')";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->execute();

        $stmt->close();
    }

    public function transformGoalDefinitionIntoSQL($goal, $issueId, $projectId, $clientId) {

        $value = mb_strtolower($goal['definition']);
        $currentSLAId = $goal['help_sla_id'];
        $curentSLA = UbirimiContainer::get()['repository']->get(Sla::class)->getById($currentSLAId);

        $SLAs = UbirimiContainer::get()['repository']->get(Sla::class)->getByProjectId($projectId);
        while ($SLAs && $SLA = $SLAs->fetch_array(MYSQLI_ASSOC)) {

            if (($index = stripos(mb_strtolower($value), mb_strtolower($SLA['name']))) !== false) {
                $index--;
                $notApplicable = false;

                while ($index > 0) {
                    if ($value[$index] == ' ') {
                        $index--;
                        continue;
                    } else if ($value[$index] == '=') {
                        $notApplicable = true;
                        break;
                    }
                }

                if ($notApplicable) {
                    continue;
                }

                if ($curentSLA['id'] != $SLA['id']) {
                    $value = str_ireplace($SLA['name'], '(select value from yongo_issue_sla where yongo_issue_id = ' . $issueId . ' and help_sla_id = ' . $SLA['id'] . ' limit 1) ', $value);
                } else {
                    $value = str_ireplace($SLA['name'], '(select NULL from yongo_issue_sla where yongo_issue_id = ' . $issueId . ' and help_sla_id = ' . $SLA['id'] . ' limit 1) ', $value);
                }
            }
        }

        $value = str_ireplace('priority', 'priority_id', $value);
        $value = str_ireplace('type', 'type_id', $value);
        $value = str_ireplace('status', 'status_id', $value);
        $value = str_ireplace('resolution', 'resolution_id', $value);
        $value = str_ireplace('assignee', 'user_assigned_id', $value);
        $value = str_ireplace('reporter', 'user_reported_id', $value);

        $statuses = UbirimiContainer::get()['repository']->get(IssueSettings::class)->getAllIssueSettings('status', $clientId);
        $priorities = UbirimiContainer::get()['repository']->get(IssueSettings::class)->getAllIssueSettings('priority', $clientId);
        $resolutions = UbirimiContainer::get()['repository']->get(IssueSettings::class)->getAllIssueSettings('resolution', $clientId);
        $types = UbirimiContainer::get()['repository']->get('yongo.issue.type')->getAll($clientId);

        while ($statuses && $status = $statuses->fetch_array(MYSQLI_ASSOC)) {
            $value = str_ireplace($status['name'], $status['id'], $value);
        }

        while ($priorities && $priority = $priorities->fetch_array(MYSQLI_ASSOC)) {
            $value = str_ireplace($priority['name'], $priority['id'], $value);
        }

        while ($resolutions && $resolution = $resolutions->fetch_array(MYSQLI_ASSOC)) {
            $value = str_ireplace($resolution['name'], $resolution['id'], $value);
        }

        while ($types && $type = $types->fetch_array(MYSQLI_ASSOC)) {
            $value = str_ireplace($type['name'], $type['id'], $value);
        }

        $query = 'select yongo_issue.id ' .
                 'from yongo_issue_sla ' .
                 'left join yongo_issue on yongo_issue.id = yongo_issue_sla.yongo_issue_id ' .
                 'where yongo_issue_sla.yongo_issue_id = ' . $issueId . ' and ' . $value;

        return $query;
    }

    public function checkConditionOnIssue($slaCondition, $issue, $historyData, $type, $currentSLADate) {

        $conditions = explode("#", $slaCondition);
        $conditionFulfilledDate = null;

        for ($i = 0; $i < count($conditions); $i++) {
            if ($conditions[$i] == ($type . '_' . Sla::CONDITION_CREATE_ISSUE)) {
                if ($issue['date_created'] >= $currentSLADate) {
                    $conditionFulfilledDate = $issue['date_created'];
                    break;
                }
            } else if ($conditions[$i] == $type . '_' . Sla::CONDITION_RESOLUTION_SET) {
                if ($issue['resolution']) {
                    if ($issue['date_resolved'] >= $currentSLADate) {
                        $conditionFulfilledDate = $issue['date_resolved'];
                        break;
                    }
                }
            } else if (strpos($conditions[$i], $type . '_status_set_') !== false) {
                if (isset($historyData['field'])) {

                    if (Field::FIELD_STATUS_CODE == $historyData['field'] && $historyData['new_value_id'] == str_replace($type . '_status_set_',  '', $conditions[$i])) {

                        if ($historyData['date_created'] >= $currentSLADate) {
                            $conditionFulfilledDate = $historyData['date_created'];
                            break;
                        }
                    }
                }
            } else if (strpos($conditions[$i], $type . '_comment_by_assignee') !== false) {
                $assigneeID = UbirimiContainer::get()['repository']->get(Issue::class)->getAssigneeOnDate($issue['id'], $currentSLADate);

                $comments = UbirimiContainer::get()['repository']->get(IssueComment::class)->getByUserIdAfterDate($issue['id'], $assigneeID, $currentSLADate);
                if ($comments) {
                    $comment = $comments->fetch_array(MYSQLI_ASSOC);
                    if ($conditionFulfilledDate) {
                        if ($comment['date_created'] >= $conditionFulfilledDate) {
                            $conditionFulfilledDate = $comment['date_created'];
                        }
                    } else {
                        $conditionFulfilledDate = $comment['date_created'];
                    }
                } else {
                    $userAssigneeId = $issue['assignee'];
                    $comments = UbirimiContainer::get()['repository']->get(IssueComment::class)->getByIssueIdAndUserId($issue['id'], $userAssigneeId);

                    if ($comments) {
                        $firstComment = $comments->fetch_array(MYSQLI_ASSOC);
                        $conditionFulfilledDate = $firstComment['date_created'];
                    }
                }
            } else if (strpos($conditions[$i], $type . '_assignee_changed') !== false) {
                $userAssigneeId = $issue['assignee'];

                // look also in the history
                $historyList = History::getByAssigneeNewChangedAfterDate($issue['id'], $userAssigneeId, $currentSLADate);

                if ($historyList) {
                    $history = $historyList->fetch_array(MYSQLI_ASSOC);
                    if ($conditionFulfilledDate) {
                        if ($history['date_created'] >= $conditionFulfilledDate) {
                            $conditionFulfilledDate = $history['date_created'];
                        }
                    } else {
                        $conditionFulfilledDate = $history['date_created'];
                    }
                }
            }
        }

        return $conditionFulfilledDate;
    }

    public function getSLAData($issueId, $SLAId) {
        $query = 'select * from yongo_issue_sla where yongo_issue_id = ? and help_sla_id = ? limit 1 ';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $issueId, $SLAId);

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return false;
    }

    public function getGoalForIssueId($slaId, $issueId, $projectId, $clientId) {
        $goals = UbirimiContainer::get()['repository']->get(Sla::class)->getGoals($slaId);
        $goalValue = null;
        $goalId = null;
        $goalCalendarId = null;

        // find what goal applies to this issue
        while ($goals && $goal = $goals->fetch_array(MYSQLI_ASSOC)) {
            if ('all_remaining_issues' == $goal['definition']) {
                $goalValue = $goal['value'];
                $goalId = $goal['id'];
                $goalCalendarId = $goal['help_sla_calendar_id'];
            } else {
                $definitionSQL = UbirimiContainer::get()['repository']->get(Sla::class)->transformGoalDefinitionIntoSQL($goal, $issueId, $projectId, $clientId);

                $issueFound = false;
                if ($stmtGoal = UbirimiContainer::get()['db.connection']->prepare($definitionSQL)) {
                    $stmtGoal->execute();
                    $resultGoal = $stmtGoal->get_result();
                    if ($resultGoal->num_rows) {
                        $issueFound = true;
                    }
                }

                if ($issueFound) {
                    $goalValue = $goal['value'];
                    $goalId = $goal['id'];
                    $goalCalendarId = $goal['help_sla_calendar_id'];
                    break;
                }
            }
        }

        return array('value' => $goalValue, 'id' => $goalId, 'goalCalendarId' => $goalCalendarId);
    }

    public function getOffsetForIssue($SLA, $issue, $clientId, $clientSettings) {
        $issueId = $issue['id'];
        $goalData = UbirimiContainer::get()['repository']->get(Sla::class)->getGoalForIssueId($SLA['id'], $issueId, $issue['issue_project_id'], $clientId);
        $goalId = $goalData['id'];

        if ($goalId == null) {
            return null;
        }
        $goalValue = $goalData['value'];
        $slaCalendarData = SlaCalendar::getCalendarDataByCalendarId($goalData['goalCalendarId']);

        $SLA = UbirimiContainer::get()['repository']->get(Sla::class)->getById($SLA['id']);

        $historyData = History::getByIssueIdAndUserId($issueId, null, 'asc', 'array');

        if (!$historyData) {
            $historyData[] = array('date_created' => $issue['date_created']);
        } else {
            array_unshift($historyData, array('date_created' => $issue['date_created']));
        }

        $startConditionSLADates = array();
        $stopConditionSLADates = array();
        $intervalMinutes = 0;

        // find start and stop dates
        foreach ($historyData as $history) {
            $startConditionSLADate = UbirimiContainer::get()['repository']->get(Sla::class)->checkConditionOnIssue($SLA['start_condition'], $issue, $history, 'start', $history['date_created']);
            $stopConditionSLADate = UbirimiContainer::get()['repository']->get(Sla::class)->checkConditionOnIssue($SLA['stop_condition'], $issue, $history, 'stop', $history['date_created']);

            if ($startConditionSLADate && !in_array($startConditionSLADate, $startConditionSLADates) && $startConditionSLADate > end($stopConditionSLADates)) {
                if (count($startConditionSLADates) - count($stopConditionSLADates) == 0) {
                    $startConditionSLADates[] = $startConditionSLADate;
                }
            }

            if ($stopConditionSLADate && !in_array($stopConditionSLADate, $stopConditionSLADates)) {
                for ($i = 0; $i < count($slaCalendarData); $i++) {
                    if (date("N", strtotime($stopConditionSLADate)) == $slaCalendarData[$i]['day_number'] && $slaCalendarData[$i]['not_working_flag'] == 0) {
                        $timeFrom = $slaCalendarData[$i]['time_from'] . ':00';
                        $timeTo = $slaCalendarData[$i]['time_to'] . ':00';
                        $timeSectionInStopDate = substr($stopConditionSLADate, 11);
                        if ($timeSectionInStopDate >= $timeFrom && $timeSectionInStopDate <= $timeTo) {
                            $stopConditionSLADates[] = $stopConditionSLADate;
                        }
                    }
                }
            }
        }

        for ($i = 0; $i < count($startConditionSLADates); $i++) {
            $startDate = $startConditionSLADates[$i];

            if (isset($stopConditionSLADates[$i])) {
                $stopDate = $stopConditionSLADates[$i];
            } else {
                $stopDate = new \DateTime('now', new \DateTimeZone($clientSettings['timezone']));
                $stopDate = date_format($stopDate, 'Y-m-d H:i:s');
            }

            $initialDate = new \DateTime($startDate, new \DateTimeZone($clientSettings['timezone']));
            while (date_format($initialDate, 'Y-m-d') <= substr($stopDate, 0, 10)) {
                $dayNumber = date_format($initialDate, 'N');

                for ($j = 0; $j < count($slaCalendarData); $j++) {
                    if ($slaCalendarData[$j]['day_number'] == $dayNumber && 0 == $slaCalendarData[$j]['not_working_flag']) {

                        if (date_format($initialDate, 'Y-m-d') > $startDate) {
                            $startCalendarHour = substr($slaCalendarData[$j]['time_from'], 0, 2);
                            $startCalendarMinute = substr($slaCalendarData[$j]['time_from'], 3, 2);
                            if ($startCalendarHour[0] == '0') {
                                $startCalendarHour = substr($startCalendarHour, 1);
                            }

                            if ($startCalendarMinute[0] == '0') {
                                $startCalendarMinute = substr($startCalendarHour, 1);
                            }
                            $initialDate->setTime($startCalendarHour, $startCalendarMinute, 0);
                        }

                        if (date_format($initialDate, 'Y-m-d') > substr($startDate, 0, 10)) {
                            $startConditionSLADate = new \DateTime(date_format($initialDate, 'Y-m-d') . ' ' . $slaCalendarData[$j]['time_from'], new \DateTimeZone($clientSettings['timezone']));
                        } else {
                            if (date_format($initialDate, 'H:i:00') <= $slaCalendarData[$j]['time_from']) {
                                $startConditionSLADate = new \DateTime(date_format($initialDate, 'Y-m-d') . ' ' . $slaCalendarData[$j]['time_from'], new \DateTimeZone($clientSettings['timezone']));
                            } else if (date_format($initialDate, 'H:i:00') > $slaCalendarData[$j]['time_to']) {
                                $startConditionSLADate = new \DateTime(date_format($initialDate, 'Y-m-d') . ' ' . $slaCalendarData[$j]['time_to'], new \DateTimeZone($clientSettings['timezone']));
                            }

                            else {
                                $startConditionSLADate = new \DateTime($startDate, new \DateTimeZone($clientSettings['timezone']));
                            }
                        }

                        if (date_format($initialDate, 'Y-m-d') < substr($stopDate, 0, 10)) {
                            $stopConditionSLADate = new \DateTime(date_format($initialDate, 'Y-m-d') . ' ' . $slaCalendarData[$j]['time_to'], new \DateTimeZone($clientSettings['timezone']));
                        } else {
                            if (date_format($initialDate, 'H:i:00') <= $slaCalendarData[$j]['time_to']) {
                                $stopConditionSLADate = new \DateTime($stopDate, new \DateTimeZone($clientSettings['timezone']));
                            } else {
                                $stopConditionSLADate = new \DateTime(substr($stopDate, 0, 10) . ' ' . $slaCalendarData[$j]['time_to'], new \DateTimeZone($clientSettings['timezone']));
                            }
                        }

                        if ($goalData['value']) {
                            $intervalMinutes += floor(($stopConditionSLADate->getTimestamp() - $startConditionSLADate->getTimestamp()) / 60);
                        }
                    }
                }

                date_add($initialDate, date_interval_create_from_date_string('1 days'));
            }
        }

        $slaFinished = false;
        if (end($startConditionSLADates) < end ($stopConditionSLADates)) {
            $slaFinished = true;
        }

        return array('slaId' => $SLA['id'],
                     'name' => $SLA['name'],
                     'intervalMinutes' => $intervalMinutes,
                     'goalValue' => $goalValue,
                     'goalId' => $goalId,
                     'finished' => $slaFinished,
                     'startDate' => $startConditionSLADates[0],
                     'endDate' => end($stopConditionSLADates));
    }

    public function updateDataForSLA($issueId, $SLAId, $intervalMinutes, $goalId, $startedDate, $stoppedDate) {
        $query = "update yongo_issue_sla set `value` = ?, help_sla_goal_id = ? ";

        if ($startedDate) {
            $query .= ", started_flag = 1, started_date = '" . $startedDate . "' ";
        } else {
            $query .= ", started_flag = 0, started_date = NULL ";
        }

        if ($stoppedDate) {
            $query .= ", stopped_flag = 1, stopped_date = '" . $startedDate . "' ";
        } else {
            $query .= ", stopped_flag = 0, stopped_date = NULL ";
        }

        $query .= " where yongo_issue_id = ? and help_sla_id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("iiii", $intervalMinutes, $goalId, $issueId, $SLAId);
        $stmt->execute();
    }

    public function checkSLABelongsToProject($slaId, $projectId) {
        $query = 'select id from help_sla where id = ? and project_id = ?';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $slaId, $projectId);

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result;
        else
            return false;
    }

    public function transformConditionForView($condition) {
        $condition = str_replace(array('start_', 'stop_'), '' , $condition);
        if (substr($condition, 0, 11) == 'status_set_') {
            $StatusId = str_replace('status_set_', '', $condition);
            $statusName = UbirimiContainer::get()['repository']->get(IssueSettings::class)->getById($StatusId, 'status', 'name');
            $condition = 'Status Set ' . $statusName;
        } else if (substr($condition, 0, 11) == 'comment_by_assignee') {
            $condition = 'Comment: By Assignee';
        } else {
            $condition = str_replace('_', ' ', $condition);
        }

        return ucwords($condition);
    }

    public function deleteGoalsBySLAId($slaId) {
        $query = "delete from help_sla_goal where help_sla_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $slaId);
        $stmt->execute();
    }

    public function updateById($slaId, $name, $description, $startCondition, $stopCondition, $date) {
        $query = "update help_sla set name = ?, description = ?, start_condition = ?, stop_condition = ?, date_updated = ? where id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("sssssi", $name, $description, $startCondition, $stopCondition, $date, $slaId);
        $stmt->execute();
    }

    public function formatOffset($value) {
        $hours = floor(abs($value) / 60);
        $minutes = (abs($value) % 60);
        $sign = '';
        if ($value < 0) {
            $sign = '-';
        }

        return sprintf('%s%s:%s', $sign, $hours, $minutes);
    }

    public function getGoalById($goalId) {
        $query = 'select * from help_sla_goal where id = ? limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $goalId);

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return false;
    }

    public function getByCalendarId($clientId, $calendarId) {
        $query = 'select help_sla.* ' .
            'from help_sla ' .
            'left join project on project.id = help_sla.project_id ' .
            'left join help_sla_goal on help_sla_goal.help_sla_id = help_sla.id ' .
            'where project.client_id = ? and help_sla_goal.help_sla_calendar_id = ? ';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $clientId, $calendarId);

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result;
        else
            return false;
    }

    public function getIssues($slaId, $dateFrom, $dateTo) {
        $query = 'select yongo_issue.id, yongo_issue.date_created, (help_sla_goal.value - yongo_issue_sla.value) as sla_value, yongo_issue_sla.stopped_date ' .
                 'from yongo_issue_sla ' .
                 'left join help_sla_goal on help_sla_goal.help_sla_id = yongo_issue_sla.help_sla_id ' .
                 'left join yongo_issue on yongo_issue.id = yongo_issue_sla.yongo_issue_id ' .
                 'where yongo_issue_sla.help_sla_id = ? and ' .
                 '(yongo_issue_sla.stopped_date >= ? and yongo_issue_sla.stopped_date <= ?)';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("iss", $slaId, $dateFrom, $dateTo);

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result;
        else
            return false;
    }
}