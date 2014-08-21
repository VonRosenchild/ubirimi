<?php

namespace Ubirimi\Repository\HelpDesk;

use Ubirimi\Container\UbirimiContainer;

class SLACalendar
{
    public static function getByProjectId($projectId) {
        $query = 'SELECT * from help_sla_calendar where project_id = ? order by id desc';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("i", $projectId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public static function getById($Id) {
        $query = 'SELECT * from help_sla_calendar where id = ? limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("i", $Id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return null;
    }

    public static function getByName($name, $projectId, $slaCalendarId = null) {
        $query = 'select id, name from help_sla_calendar where project_id = ? and LOWER(name) = LOWER(?) ';
        if ($slaCalendarId) {
            $query .= 'and id != ?';
        }

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        if ($slaCalendarId)
            $stmt->bind_param("isi", $projectId, $name, $slaCalendarId);
        else
            $stmt->bind_param("is", $projectId, $name);

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result;
        else
            return false;
    }

    public static function save($projectId, $timezoneId, $name, $description, $date) {
        $query = "INSERT INTO help_sla_calendar(project_id, sys_timezone_id, name, description, date_created) VALUES " .
            "(?, ?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("iisss", $projectId, $timezoneId, $name, $description, $date);
        $stmt->execute();

        return UbirimiContainer::get()['db.connection']->insert_id;
    }

    public static function deleteDataByCalendarId($calendarId) {
        $query = "delete from help_sla_calendar_data where help_sla_calendar_id = ?";
        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $calendarId);
        $stmt->execute();
        $stmt->close();
    }

    public static function deleteById($Id) {
        $query = "delete from help_sla_calendar where id = ? limit 1";
        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $Id);
        $stmt->execute();
        $stmt->close();

        SLACalendar::deleteDataByCalendarId($Id);
    }

    public static function getData($slaCalendarId) {
        $query = 'select * from help_sla_calendar_data where help_sla_calendar_id = ?';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $slaCalendarId);

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result;
        else
            return false;
    }

    public static function updateById($slaCalendarId, $timezoneId, $name, $description, $date) {
        $query = "update help_sla_calendar set name = ?, description = ?, sys_timezone_id = ?, date_updated = ? where id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ssisi", $name, $description, $timezoneId, $date, $slaCalendarId);
        $stmt->execute();
    }

    public static function getCalendars($clientId) {
        $query = 'select * from help_sla_calendar where client_id = ?';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $clientId);

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result;
        else
            return false;
    }

    public static function addData($calendarId, $data) {
        for ($i = 1; $i <= 7; $i++) {
            $query = "INSERT INTO help_sla_calendar_data(help_sla_calendar_id, day_number, time_from, time_to, not_working_flag) VALUES " .
                "(?, ?, ?, ?, ?)";

            $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
            if ($data[$i-1]['notWorking']) {
                $startTime = null;
                $endTime = null;
            } else {
                $startTime = $data[$i - 1]['from_hour'] . ':' . $data[$i - 1]['from_minute'];
                $endTime = $data[$i - 1]['to_hour'] . ':' . $data[$i - 1]['to_minute'];
            }

            $stmt->bind_param("iissi", $calendarId, $i, $startTime, $endTime, $data[$i - 1]['notWorking']);
            $stmt->execute();
        }
    }

    public static function addCalendar($projectId, $name, $description, $data, $defaultFlag = 0, $date) {

        $query = "INSERT INTO help_sla_calendar(project_id, name, description, default_flag, date_created) VALUES " .
            "(?, ?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("issis", $projectId, $name, $description, $defaultFlag, $date);
        $stmt->execute();
        $calendarId = UbirimiContainer::get()['db.connection']->insert_id;

        // add the data
        SLACalendar::addData($calendarId, $data);

        return $calendarId;
    }

    public static function getCalendarDataByCalendarId($calendarId) {
        $query = 'select * from help_sla_calendar_data where help_sla_calendar_id = ? order by day_number';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
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
