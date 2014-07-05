<?php

namespace Ubirimi\Repository\HelpDesk;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Issue\IssueSettings;
use Ubirimi\Yongo\Repository\Issue\IssueType;

class SLA {

    const CONDITION_CREATE_ISSUE = 'issue_created';
    const CONDITION_RESOLUTION_SET = 'resolution_set';

    public static function getByProjectId($projectId) {
        $query = 'SELECT * from help_sla where project_id = ? order by id desc';

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("i", $projectId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result;
            else
                return null;
        }
    }

    public static function getByProjectIds($projectIds) {
        $query = 'SELECT * from help_sla where project_id IN (' . implode(', ', $projectIds) . ') order by id desc';

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result;
            else
                return null;
        }
    }

    public static function getById($Id) {
        $query = 'SELECT * from help_sla where id = ? limit 1';

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("i", $Id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result->fetch_array(MYSQLI_ASSOC);
            else
                return null;
        }
    }

    public static function getByName($name, $projectId, $slaId = null) {
        $query = 'select id, name from help_sla where project_id = ? and LOWER(name) = LOWER(?) ';
        if ($slaId) {
            $query .= 'and id != ?';
        }

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
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
    }

    public static function save($projectId, $name, $description, $startCondition, $stopCondition, $date) {
        $query = "INSERT INTO help_sla(project_id, name, description, start_condition, stop_condition, date_created) VALUES " .
            "(?, ?, ?, ?, ?, ?)";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("isssss", $projectId, $name, $description, $startCondition, $stopCondition, $date);
            $stmt->execute();
            return UbirimiContainer::get()['db.connection']->insert_id;
        }
    }

    public static function addGoal($slaId, $definition, $definitionSQL, $value) {
        $query = "INSERT INTO help_sla_goal(help_sla_id, definition, definition_sql, value) VALUES " .
                 "(?, ?, ?, ?)";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("isss", $slaId, $definition, $definitionSQL, $value);
            $stmt->execute();
            return UbirimiContainer::get()['db.connection']->insert_id;
        }
    }

    public static function getGoals($slaId) {
        $query = 'select * from help_sla_goal where help_sla_id = ?';

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $slaId);

            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows)
                return $result;
            else
                return false;
        }
    }

    public static function deleteById($Id) {

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

    public static function transformGoalDefinitionIntoSQL($goal, $issueId, $projectId, $clientId) {

        $value = mb_strtolower($goal['definition']);
        $currentSLAId = $goal['help_sla_id'];
        $curentSLA = SLA::getById($currentSLAId);

        $SLAs = SLA::getByProjectId($projectId);
        while ($SLAs && $SLA = $SLAs->fetch_array(MYSQLI_ASSOC)) {
            if (stripos(mb_strtolower($value), mb_strtolower($SLA['name'])) !== false) {
                if ($curentSLA['start_condition'] != $SLA['start_condition']) {
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

        $statuses = IssueSettings::getAllIssueSettings('status', $clientId);
        $priorities = IssueSettings::getAllIssueSettings('priority', $clientId);
        $resolutions = IssueSettings::getAllIssueSettings('resolution', $clientId);
        $types = IssueType::getAll($clientId);

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

    public static function checkConditionOnIssue($slaCondition, $issue, $type) {

        $conditionFulfilledDate = null;
        $conditions = explode("#", $slaCondition);

        for ($i = 0; $i < count($conditions); $i++) {

            if ($conditions[$i] == ($type . '_' . SLA::CONDITION_CREATE_ISSUE)) {
                if (!$conditionFulfilledDate || $conditionFulfilledDate < $issue['created']) {
                    $conditionFulfilledDate = $issue['date_created'];
                }
            } else if ($conditions[$i] == $type . '_' . SLA::CONDITION_RESOLUTION_SET) {

                if ($issue['resolution']) {
                    if (!$conditionFulfilledDate || $conditionFulfilledDate < $issue['created']) {
                        $conditionFulfilledDate = $issue['date_resolved'];
                    }
                }
            } else if (strpos($conditions[$i], $type . '_status_set_') !== false) {
                if ($issue['status'] == str_replace($type . '_status_set_',  '', $conditions[$i])) {

                    if (!$conditionFulfilledDate || $conditionFulfilledDate < $issue['created']) {
                        $conditionFulfilledDate = $issue['date_updated'];
                    }
                }
            }
        }

        return $conditionFulfilledDate;
    }

    public static function getSLAData($issueId, $SLAId) {
        $query = 'select * from yongo_issue_sla where yongo_issue_id = ? and help_sla_id = ? limit 1 ';

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("ii", $issueId, $SLAId);

            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows)
                return $result->fetch_array(MYSQLI_ASSOC);
            else
                return false;
        }
    }

    public static function getGoalForIssueId($slaId, $issueId, $projectId, $clientId) {
        $goals = SLA::getGoals($slaId);
        $goalValue = null;
        $goalId = null;
        // find what goal applies to this issue
        while ($goals && $goal = $goals->fetch_array(MYSQLI_ASSOC)) {

            $definition = $goal['definition'];
            if ('all_remaining_issues' == $definition) {
                $definition = '';
            }

            $definitionSQL = SLA::transformGoalDefinitionIntoSQL($goal, $issueId, $projectId, $clientId);

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
                break;
            }
        }

        return array('value' => $goalValue, 'id' => $goalId);
    }

    public static function getOffsetForIssue($SLA, $issue, $clientId, $clientSettings) {
        $issueId = $issue['id'];

        $issueSLAData = SLA::getSLAData($issueId, $SLA['id']);
        $SLA = SLA::getById($SLA['id']);
        $slaCalendarData = SLA::getCalendarDataByCalendarId($SLA['help_sla_calendar_id']);
        $stopConditionDate = null;
        $intervalMinutes = null;
        $goalValue = null;
        $goalId = null;
        $startConditionDate = null;

        $initialDate = new \DateTime($issue['date_created'], new \DateTimeZone($clientSettings['timezone']));
        $currentDateObject = new \DateTime('now', new \DateTimeZone($clientSettings['timezone']));

        $currentHourMinuteSecond = date_format($currentDateObject, 'H:i:00');
        $currentDate = date_format($currentDateObject, 'Y-m-d');

        $finalDate = new \DateTime('now', new \DateTimeZone($clientSettings['timezone']));
        $finalDate = date_create($finalDate, 'Y-m-d');

        while (date_format($initialDate, 'Y-m-d') <= $finalDate) {
            $dayNumber = date_format($initialDate, 'N');

            for ($i = 0; $i < count($slaCalendarData); $i++) {
                if ($slaCalendarData[$i]['day_number'] == $dayNumber) {
                    if (!$issueSLAData['started_flag']) {
                        // check if this issue has the start condition of the sla true
                        $startConditionDate = SLA::checkConditionOnIssue($SLA['start_condition'], $issue, 'start');
                        if (!$startConditionDate) {
                            return null;
                        }

                        $issueSLAData['started_flag'] = 1;
                        $dateStart = new \DateTime($startConditionDate, new \DateTimeZone($clientSettings['timezone']));
                        $dateStop = new \DateTime('now', new \DateTimeZone($clientSettings['timezone']));

                        $goalData = SLA::getGoalForIssueId($SLA['id'], $issue['id'], $issue['issue_project_id'], $clientId);
                        $goalId = $goalData['id'];
                        $goalValue = $goalData['value'];

                        $intervalMinutes = null;
                        if ($goalData['value'] && date_format('H:i:00', $dateStart) >= $slaCalendarData['time_from'] &&
                            date_format('H:i:00', $dateStop) >= $slaCalendarData['time_from']) {
                            $intervalMinutes = $goalValue - floor(($dateStop->getTimestamp() - $dateStart->getTimestamp()) / 60);
                        }
                    } else if ($issueSLAData['started_flag'] && !$issueSLAData['stopped_flag']) {
                        // check if this issue has the stop condition of the sla true
                        $stopConditionDate = SLA::checkConditionOnIssue($SLA['stop_condition'], $issue, 'stop');

                        if (!$stopConditionDate) {
                            $dateStop = new \DateTime('now', new \DateTimeZone($clientSettings['timezone']));
                        } else {
                            $dateStop = new \DateTime($stopConditionDate);
                            if (!$issueSLAData['stopped_flag']) {
                                Issue::updateSLAStopped($issueId, $SLA['id'], $dateStop->format('Y-m-d H:i:s'));
                            }
                        }

                        if ($currentHourMinuteSecond > $slaCalendarData[$i]['time_from']) {

                        }

                        $dateStart = new \DateTime($issueSLAData['started_date'], new \DateTimeZone($clientSettings['timezone']));
                        $goalData = SLA::getGoalForIssueId($SLA['id'], $issue['id'], $issue['issue_project_id'], $clientId);

                        $goalId = $goalData['id'];
                        $goalValue = $goalData['value'];

                        if ($goalValue) {
                            $intervalMinutes = $goalValue - floor(($dateStop->getTimestamp() - $dateStart->getTimestamp()) / 60);
                        }
                        $startConditionDate = $issueSLAData['started_date'];
                    } else if ($issueSLAData['started_flag'] && $issueSLAData['stopped_flag']) {

                        $dateStart = new \DateTime($issueSLAData['started_date']);
                        $dateStop = new \DateTime($issueSLAData['stopped_date']);

                        $goal = SLA::getGoalById($issueSLAData['help_sla_goal_id']);
                        $goalValue = $goal['value'];
                        $goalId = $goal['id'];
                        $intervalMinutes = $goalValue - floor(($dateStop->getTimestamp() - $dateStart->getTimestamp()) / 60);
                        $startConditionDate = $issueSLAData['started_date'];
                    }
                }
            }
            date_add($initialDate, date_interval_create_from_date_string('1 days'));
        }

        return array($intervalMinutes, $goalValue, $issueSLAData['started_flag'], $goalId, $startConditionDate);
    }

    public static function updateDataForSLA($issueId, $SLAId, $intervalMinutes, $startedFlag, $goalId, $startedDate) {
        $query = "update yongo_issue_sla set value = ?, started_flag = ?, help_sla_goal_id = ?, started_date = ? where yongo_issue_id = ? and help_sla_id = ? limit 1";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("iiisii", $intervalMinutes, $startedFlag, $goalId, $startedDate, $issueId, $SLAId);
            $stmt->execute();
        }
    }

    public static function checkSLABelongsToProject($slaId, $projectId) {
        $query = 'select id from help_sla where id = ? and project_id = ?';

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("ii", $slaId, $projectId);

            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows)
                return $result;
            else
                return false;
        }
    }

    public static function transformConditionForView($condition) {
        $condition = str_replace(array('start_', 'stop_'), '' , $condition);
        if (substr($condition, 0, 11) == 'status_set_') {
            $StatusId = str_replace('status_set_', '', $condition);
            $statusName = IssueSettings::getById($StatusId, 'status', 'name');
            $condition = 'Status Set ' . $statusName;

        } else {
            $condition = str_replace('_', ' ', $condition);
        }

        return ucwords($condition);
    }

    public static function deleteGoalsBySLAId($slaId) {
        $query = "delete from help_sla_goal where help_sla_id = ?";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $slaId);
            $stmt->execute();
        }
    }

    public static function updateById($slaId, $name, $description, $startCondition, $stopCondition, $date) {
        $query = "update help_sla set name = ?, description = ?, start_condition = ?, stop_condition = ?, date_updated = ? where id = ? limit 1";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("sssssi", $name, $description, $startCondition, $stopCondition, $date, $slaId);
            $stmt->execute();
        }
    }

    public static function formatOffset($value) {

        $hours = floor(abs($value) / 60);
        $minutes = (abs($value) % 60);
        $sign = '';
        if ($value < 0) {
            $sign = '-';
        }

        return sprintf('%s%s:%s', $sign, $hours, $minutes);
    }

    public static function getGoalById($goalId) {
        $query = 'select * from help_sla_goal where id = ? limit 1';

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $goalId);

            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows)
                return $result->fetch_array(MYSQLI_ASSOC);
            else
                return false;
        }
    }

    public static function getCalendars($clientId) {
        $query = 'select * from help_sla_calendar where client_id = ?';

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $clientId);

            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows)
                return $result;
            else
                return false;
        }
    }

    public static function addCalendar($clientId, $name, $description, $data, $date) {
        $query = "INSERT INTO help_sla_calendar(client_id, name, description, date_created) VALUES " .
            "(?, ?, ?, ?)";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("isss", $clientId, $name, $description, $date);
            $stmt->execute();
            $calendarId = UbirimiContainer::get()['db.connection']->insert_id;

            // add the data

            for ($i = 0; $i < 7; $i++) {
                $query = "INSERT INTO help_sla_calendar_data(help_sla_calendar_id, help_sla_calendar_day_id, start_time, end_time, not_working_flag) VALUES " .
                    "(?, ?, ?, ?, ?)";

                if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
                    if ($data[$i]['notWorking']) {
                        $startTime = null;
                        $endTime = null;
                    } else {
                        $startTime = $data[$i]['from_hour'] . ':' . $data[$i]['from_minute'];
                        $endTime = $data[$i]['to_hour'] . ':' . $data[$i]['to_minute'];
                    }

                    $stmt->bind_param("iissi", $calendarId, $i, $startTime, $endTime, $data[$i]['notWorking']);
                    $stmt->execute();

                }
            }
        }
    }

    public static function getCalendarDataByCalendarId($calendarId) {
        $query = 'select * from help_sla_calendar_data where help_sla_calendar_id = ?';

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $calendarId);

            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows) {
                $resultArray = array();
                while ($data = $result->fetch_array(MYSQLI_ASSOC)) {
                    $resultArray[] = $data;
                }
                return $resultArray;
            } else
                return false;
        }
    }
}