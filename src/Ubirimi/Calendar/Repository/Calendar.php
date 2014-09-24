<?php

namespace Ubirimi\Calendar\Repository;

use Ubirimi\Container\UbirimiContainer;

class Calendar
{
    public static function getByUserId($userId, $resultType = null, $resultColumn = null) {
        $query = "select cal_calendar.id, cal_calendar.name, cal_calendar.description, cal_calendar.date_created, cal_calendar.default_flag, " .
                 "cal_calendar.color " .
            "from cal_calendar " .
            "left join user on user.id = cal_calendar.user_id " .
            "where cal_calendar.user_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            if ($resultType == 'array') {
                $resultArray = array();
                while ($record = $result->fetch_array(MYSQLI_ASSOC)) {
                    if ($resultColumn)
                        $resultArray[] = $record[$resultColumn];
                    else
                        $resultArray[] = $record;
                }

                return $resultArray;
            } else {
                return $result;
            }
        } else
            return null;
    }

    public static function getSharedWithUserId($userId, $resultType = null, $resultColumn = null) {
        $query = "select cal_calendar.id, cal_calendar.name, cal_calendar.description, cal_calendar.date_created " .
            "from cal_calendar_share " .
            "left join cal_calendar on cal_calendar.id = cal_calendar_share.cal_calendar_id " .
            "where cal_calendar_share.user_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            if ($resultType == 'array') {
                $resultArray = array();
                while ($record = $result->fetch_array(MYSQLI_ASSOC)) {
                    if ($resultColumn)
                        $resultArray[] = $record[$resultColumn];
                    else
                        $resultArray[] = $record;
                }

                return $resultArray;
            } else {
                return $result;
            }
        } else {
            return null;
        }
    }

    public static function getSharedUsers($calendarId, $resultType = null) {
        $query = "select user.id, user.first_name, user.last_name " .
            "from cal_calendar_share " .
            "left join user on user.id = cal_calendar_share.user_id " .
            "where cal_calendar_share.cal_calendar_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $calendarId);
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

    public static function save($userId, $name, $description, $color, $date, $defaultFlag = null) {
        $query = "INSERT INTO cal_calendar(user_id, name, description, color, default_flag, date_created) VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("isssis", $userId, $name, $description, $color, $defaultFlag, $date);
        $stmt->execute();

        $calendarId = UbirimiContainer::get()['db.connection']->insert_id;

        return $calendarId;
    }

    public static function getById($calendarId) {
        $query = "select cal_calendar.id, cal_calendar.user_id, cal_calendar.name, cal_calendar.description, " .
                 "cal_calendar.default_flag, cal_calendar.date_created, cal_calendar.date_updated, cal_calendar.color, " .
                 "user.client_id " .
            "from cal_calendar " .
            "left join user on user.id = cal_calendar.user_id " .
            "where cal_calendar.id = ? " .
            "limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $calendarId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            return $result->fetch_array(MYSQLI_ASSOC);
        } else
            return null;
    }

    public static function getByIds($calendarIds) {
        $query = "select * " .
            "from cal_calendar " .
            "where cal_calendar.id IN (?) " .
            "limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("s", $calendarIds);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            return $result->fetch_array(MYSQLI_ASSOC);
        } else
            return null;
    }

    public static function getByName($userId, $name, $calendarId = null) {
        $query = 'select id, name, description ' .
            'from cal_calendar ' .
            'where user_id = ? ' .
            'and LOWER(name) = ? ';

        if ($calendarId)
            $query .= 'and id != ? ';

        $query .= 'limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        if ($calendarId)
            $stmt->bind_param("isi", $userId, $name, $calendarId);
        else
            $stmt->bind_param("is", $userId, $name);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return null;
    }

    public static function updateById($calendarId, $name, $description, $color, $date) {
        $query = 'UPDATE cal_calendar SET name = ?, description = ?, color = ?, date_updated = ? WHERE id = ? LIMIT 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ssssi", $name, $description, $color, $date, $calendarId);
        $stmt->execute();
    }

    public static function deleteSharesByCalendarId($calendarId) {
        $query = 'delete from cal_calendar_share WHERE cal_calendar_id = ?';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $calendarId);
        $stmt->execute();
    }

    public static function deleteById($calendarId) {
        // if calendar is shared delete the shares
        Calendar::deleteSharesByCalendarId($calendarId);

        $events = CalendarEvent::getAllByCalendarId($calendarId);
        if ($events) {
            while ($event = $events->fetch_array(MYSQLI_ASSOC)) {
                $calEventRepeatId = $event['cal_event_repeat_id'];

                $query = 'delete from cal_event_repeat WHERE id = ? limit 1';
                $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
                $stmt->bind_param("i", $calEventRepeatId);
                $stmt->execute();

                // delete the reminders
                $query = 'delete from cal_event_reminder WHERE cal_event_id = ?';
                $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
                $stmt->bind_param("i", $event['id']);
                $stmt->execute();
            }
        }

        // delete the events
        $query = 'delete from cal_event WHERE cal_calendar_id = ?';
        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $calendarId);
        $stmt->execute();

        // delete the default reminders
        $query = 'delete from cal_calendar_default_reminder WHERE cal_calendar_id = ?';
        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $calendarId);
        $stmt->execute();

        // delete the calendar
        $query = 'delete from cal_calendar WHERE id = ? LIMIT 1';
        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $calendarId);
        $stmt->execute();
    }

    public static function shareWithUsers($calendarId, $userIds, $date) {
        for ($i = 0; $i < count($userIds); $i++) {
            $query = "INSERT INTO cal_calendar_share(cal_calendar_id, user_id, date_created) VALUES (?, ?, ?)";
            $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
            $userId = $userIds[$i];
            $stmt->bind_param("iis", $calendarId, $userId, $date);
            $stmt->execute();
        }
    }

    public static function getDefaultCalendar($userId) {
        $query = "select * " .
            "from cal_calendar " .
            "where cal_calendar.user_id = ? and default_flag = 1 " .
            "limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            return $result->fetch_array(MYSQLI_ASSOC);
        } else
            return null;
    }

    public static function getAll() {
        $query = "select cal_calendar.name, cal_calendar.description, cal_calendar.date_created, client.company_domain, cal_calendar.id " .
            "from cal_calendar " .
            "left join user on user.id = cal_calendar.user_id " .
            "left join client on client.id = user.client_id " .
            "order by cal_calendar.id desc";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            return $result;
        } else
            return null;
    }

    public static function getByClientId($clientId) {
        $query = "select cal_calendar.* " .
            "from cal_calendar " .
            "left join user on cal_calendar.user_id = user.id " .
            "where user.client_id = ? " .
            "limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $clientId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            return $result;
        } else
            return null;
    }

    public static function getReminders($calendarId) {
        $query = "select cal_calendar_default_reminder.* " .
            "from cal_calendar_default_reminder " .
            "where cal_calendar_default_reminder.cal_calendar_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $calendarId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            return $result;
        } else
            return null;
    }

    public static function deleteReminders($calendarId) {
        $query = "delete from cal_calendar_default_reminder where cal_calendar_default_reminder.cal_calendar_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $calendarId);
        $stmt->execute();
    }

    public static function addReminder($calendarId, $reminderType, $reminderPeriod, $reminderValue) {
        $query = "INSERT INTO cal_calendar_default_reminder(cal_calendar_id, cal_event_reminder_type_id, cal_event_reminder_period_id, value) VALUES (?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("iiii", $calendarId, $reminderType, $reminderPeriod, $reminderValue);;
        $stmt->execute();
    }

    public static function deleteByUserId($userId) {
        $calendars = Calendar::getByUserId($userId);

        while ($calendars && $calendar = $calendars->fetch_array(MYSQLI_ASSOC)) {
            Calendar::deleteById($calendar['id']);
        }
    }

    public static function deleteReminderById($reminderId) {
        $query = "delete from cal_calendar_default_reminder where id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $reminderId);
        $stmt->execute();
    }

    public static function getEventsByLinkId($eventLinkId) {
        $query = "select cal_event.* " .
            "from cal_event " .
            "where cal_event.cal_event_link_id = ? " .
            "order by id asc";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $eventLinkId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            return $result;
        } else
            return null;
    }

    public static function updateEventsLinkByLinkId($oldLinkId, $newLinkId) {
        $query = "update cal_event set cal_event_link_id = ? where cal_event_link_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $newLinkId, $oldLinkId);
        $stmt->execute();
    }
}