<?php

namespace Ubirimi\Calendar\Repository;

use Ubirimi\Container\UbirimiContainer;
use When;

class CalendarEvent
{
    public static function add($calendarId, $userCreatedId, $name, $description, $location, $start, $end, $color, $currentDate, $repeatData = null) {
        $calEventRepeatId = null;
        $repeatDates = array();

        if ($repeatData) {
            $repeatDataArray = explode("#", $repeatData);
            $repeatType = $repeatDataArray[0];

            $daysBetween = (strtotime($end) - strtotime($start)) / 86400;

            if (CalendarEventRepeatCycle::REPEAT_DAILY == $repeatType) {

                // $repeatData format
                // repeatType#repeat_every#n|#a3|#o2013-08-08#start_date

                $repeatEvery = $repeatDataArray[1];
                $endData = $repeatDataArray[2];
                $repeatStartDate = $repeatDataArray[3];
                $repeatEndDate = null;

                if ('n' == $endData[0]) {
                    $dateTemporary = date_create($repeatStartDate);
                    date_add($dateTemporary, date_interval_create_from_date_string('30 years'));

                    $repeatEndDate = date_format($dateTemporary, 'Y-m-d');
                    $repeatEndDateTemporary = date_create($repeatStartDate);

                    while (date_format($repeatEndDateTemporary, 'Y-m-d') <= $repeatEndDate) {

                        date_add(
                            $repeatEndDateTemporary,
                            date_interval_create_from_date_string(intval($repeatEvery) . ' days')
                        );

                        $offsetEndDate = date_add(
                            $repeatEndDateTemporary,
                            date_interval_create_from_date_string($daysBetween . ' days')
                        );

                        $repeatDates[] = array(
                            date_format($repeatEndDateTemporary, 'Y-m-d'), date_format($offsetEndDate, 'Y-m-d')
                        );
                    }
                } else if ('a' == $endData[0]) {

                    $pos = 1;
                    $repeatEndDate = $repeatStartDate;
                    while ($pos < intval($endData[1])) {
                        $repeatEndDate = date(
                            'Y-m-d',
                            strtotime("+" . intval($repeatEvery) . ' days', strtotime($repeatEndDate))
                        );

                        $repeatDates[] = array(
                            $repeatEndDate,
                            date('Y-m-d', strtotime("+" . $daysBetween . ' days', strtotime($repeatEndDate)))
                        );
                        $pos++;
                    }
                }
            }

            $query = "INSERT INTO cal_event_repeat(cal_event_repeat_cycle_id, repeat_every, start_date, end_date) VALUES (?, ?, ?, ?)";
            $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

            $stmt->bind_param("iiss", $repeatType, $repeatEvery, $repeatStartDate, $repeatEndDate);;
            $stmt->execute();

            $calEventRepeatId = UbirimiContainer::get()['db.connection']->insert_id;
        }

        $query = "INSERT INTO cal_event(cal_calendar_id, user_created_id, cal_event_repeat_id, name, description, location, date_from, " .
                 "date_to, color, date_created) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $color = '#' . $color;
        $stmt->bind_param("iiisssssss",
            $calendarId,
            $userCreatedId,
            $calEventRepeatId,
            $name,
            $description,
            $location,
            $start,
            $end,
            $color,
            $currentDate
        );

        $stmt->execute();

        $eventId = UbirimiContainer::get()['db.connection']->insert_id;
        // update the cal_event_link_id
        $query = "update cal_event set cal_event_link_id = ? where id = ? limit 1";
        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("ii", $eventId, $eventId);
        $stmt->execute();

        $query = "INSERT INTO cal_event(cal_calendar_id, user_created_id, cal_event_link_id, cal_event_repeat_id, name, description, location, date_from, " .
                                       "date_to, color, date_created) VALUES ";
        $separator = '';
        if (count($repeatDates)) {
            for ($k = 0; $k < count($repeatDates); $k++) {
                $queryValues = $separator . "(%d, %d, %d, %d, '%s', '%s', '%s', '%s', '%s', '%s', '%s')";
                $query .= sprintf(
                    $queryValues,
                    $calendarId,
                    $userCreatedId,
                    $eventId,
                    $calEventRepeatId,
                    $name,
                    $description,
                    $location,
                    $repeatDates[$k][0],
                    $repeatDates[$k][1],
                    $color,
                    $currentDate
                );

                $separator = ',';
            }
        } else {
            $queryValues = $separator . "(%d, %d, %d, %d, '%s', '%s', '%s', '%s', '%s', '%s', '%s')";
            $query .= sprintf(
                $queryValues,
                $calendarId,
                $userCreatedId,
                $eventId,
                $calEventRepeatId,
                $name,
                $description,
                $location,
                null,
                null,
                $color,
                $currentDate
            );
        }

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->execute();

        return $eventId;
    }

    public static function getByCalendarId($calendarId, $filterStartDate, $filterEndDate, $defaultCalendarSelected = null, $userId = null, $resultType = null) {
        $query = "SELECT cal_event.id, cal_event.user_created_id, cal_event.date_from, cal_event.date_to, cal_event.name, cal_event.description, cal_event.color, cal_event.location, " .
            "cal_calendar.name as calendar_name, 1 as own_event, TIMESTAMPDIFF(SECOND, cal_event.date_from, cal_event.date_to) as timediff, cal_calendar.color as calendar_color, " .
            "cal_event.cal_event_repeat_id, cal_event.cal_event_link_id " .
            "FROM cal_event " .
            "left join cal_calendar on cal_calendar.id = cal_event.cal_calendar_id " .
            "WHERE " .
            "cal_event.cal_calendar_id IN (" . $calendarId . ") and " .
            "cal_event.date_from <= ? and " .
            "cal_event.date_to >= ?";

        // if $defaultCalendarSelected is true include the events that you are guest at
        if ($defaultCalendarSelected) {
            $query .= ' UNION ' .
                "SELECT cal_event.id, cal_event.user_created_id, cal_event.date_from, cal_event.date_to, cal_event.name, cal_event.description, cal_event.color, cal_event.location, " .
                "cal_calendar.name as calendar_name, 0 as own_event, TIMESTAMPDIFF(SECOND, cal_event.date_from, cal_event.date_to) as timediff, cal_calendar.color as calendar_color, " .
                "cal_event.cal_event_repeat_id, cal_event.cal_event_link_id " .
                "FROM cal_event_share " .
                "left join cal_event on cal_event.id = cal_event_share.cal_event_id " .
                "left join cal_calendar on cal_calendar.id = cal_event.cal_calendar_id " .
                "WHERE " .
                "cal_event_share.user_id = ? and " .
                "cal_event.date_from <= ? and " .
                "cal_event.date_to >= ?";
        }

        $query .= ' order by timediff desc';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        if ($defaultCalendarSelected) {
            $stmt->bind_param("ssiss", $filterEndDate, $filterStartDate, $userId, $filterEndDate, $filterStartDate);
        } else {
            $stmt->bind_param("ss", $filterEndDate, $filterStartDate);
        }

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

        }

        return null;
    }

    public static function getByCalendarIds($calendarIds, $filterStartDate, $filterEndDate, $resultType = null) {
        $query = "SELECT cal_event.id, cal_event.user_created_id, cal_event.date_from, cal_event.date_to, cal_event.name, cal_event.description, cal_event.color " .
            "FROM cal_event " .
            "WHERE " .
            "cal_event.cal_calendar_id IN (?) and " .
            "cal_event.date_from <= ? and " .
            "cal_event.date_to >= ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("sss", $calendarIds, $filterEndDate, $filterStartDate);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
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
            }
        }

        return null;
    }

    public static function getById($eventId, $resultType = null) {
        $query = "SELECT cal_event.id, cal_event.user_created_id, cal_event.date_from, cal_event.date_to, cal_event.name, cal_event.description, cal_event.color, cal_event.location, " .
            "cal_event.cal_event_link_id, " .
            "cal_calendar.name as calendar_name, cal_calendar.id as calendar_id, " .
            "user.client_id " .
            "FROM cal_event " .
            "left join cal_calendar on cal_calendar.id = cal_event.cal_calendar_id " .
            "left join user on user.id = cal_calendar.user_id " .
            "WHERE " .
                "cal_event.id = ? " .
            "limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $eventId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            if ($resultType == 'array') {
                return $result->fetch_array(MYSQLI_ASSOC);
            } else {
                return $result;
            }
        } else
            return null;
    }

    public static function updateById($eventId, $calendarId, $name, $description, $location, $dateFrom, $dateTo, $color, $dateUpdated) {
        $query = "update cal_event
                    set
                        cal_calendar_id = ?,
                        name = ?,
                        description = ?,
                        location = ?,
                        date_from = ?,
                        date_to = ?,
                        color = ?,
                        date_updated = ?
                    where id = ?
                    limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("isssssssi",
            $calendarId,
            $name,
            $description,
            $location,
            $dateFrom,
            $dateTo,
            $color,
            $dateUpdated,
            $eventId
        );

        $stmt->execute();
    }

    public static function updateEventOffset($eventId, $offset) {
        $event = CalendarEvent::getById($eventId, 'array');
        $startDateEvent = $event['date_from'];

        if ($startDateEvent < $offset) {
            $query = "update cal_event set date_from = DATE_ADD(date_from, INTERVAL DATEDIFF(?, date_from) DAY), " .
                "date_to = DATE_ADD(date_to, INTERVAL DATEDIFF(?, date_to) DAY) " .
                "where id = ? limit 1";
        }

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("ssi", $offset, $offset, $eventId);
        $stmt->execute();
    }

    public static function shareWithUsers($eventId, $userIds, $date) {
        // first delete the users we try to add to avoid duplicated
        $query = "delete from cal_event_share where cal_event_id = ? and user_id IN (" . implode(', ', $userIds) . ');';
        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $eventId);
        $stmt->execute();

        for ($i = 0; $i < count($userIds); $i++) {
            $query = "INSERT INTO cal_event_share(cal_event_id, user_id, date_created) VALUES (?, ?, ?)";
            $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
            $userId = $userIds[$i];
            $stmt->bind_param("iis", $eventId, $userId, $date);
            $stmt->execute();
        }
    }

    public static function getShareByUserIdAndEventId($userId, $eventId) {
        $query = "select * " .
            "from cal_event_share " .
            "where cal_event_share.user_id = ? and cal_event_share.cal_event_id = ? ";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $userId, $eventId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            return $result->fetch_array(MYSQLI_ASSOC);
        } else
            return null;
    }

    public static function deleteEventSharesByUserId($eventId, $userId) {
        $query = "delete from cal_event_share where cal_event_id = ? and user_id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $eventId, $userId);
        $stmt->execute();
    }

    public static function deleteAllEventShares($eventId) {
        $query = "delete from cal_event_share where cal_event_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $eventId);
        $stmt->execute();
    }

    public static function deleteById($eventId, $recurringType = null) {
        // delete the shares
        CalendarEvent::deleteAllEventShares($eventId);

        // delete the reminders
        CalendarEvent::deleteReminders($eventId);

        switch ($recurringType) {
            case 'all_following':
                $event = CalendarEvent::getById($eventId, 'array');
                $query = "delete from cal_event where id >= ? and cal_event_link_id = ?";
                $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
                $stmt->bind_param("ii", $eventId, $event['cal_event_link_id']);
                $stmt->execute();

                break;

            case 'all_series':
                $event = CalendarEvent::getById($eventId, 'array');
                $query = "delete from cal_event where id = ? or cal_event_link_id = ?";

                $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
                $stmt->bind_param("ii", $event['cal_event_link_id'], $event['cal_event_link_id']);
                $stmt->execute();

                break;

            case 'me':
            case null:
                $query = "delete from cal_event where id = ? limit 1";

                $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
                $stmt->bind_param("i", $eventId);
                $stmt->execute();
        }
    }

    public static function getGuests($eventId) {
        $query = "select user.* " .
            "from cal_event_share " .
            "left join user on user.id = cal_event_share.user_id " .
            "where cal_event_share.cal_event_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $eventId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            return $result;
        } else
            return null;
    }

    public static function getByText($userId, $query) {
        $query = "select cal_event.id, cal_event.name, cal_event.description, cal_event.date_from, cal_event.date_to, " .
                 "cal_calendar.name as calendar_name, cal_event_share.id as shared_event " .
            "from cal_calendar " .
            "left join cal_event on cal_event.cal_calendar_id = cal_calendar.id " .
            "left join cal_event_share on (cal_event_share.cal_event_id = cal_event.id and cal_event_share.user_id = ?) " .
            "where " .
            "cal_calendar.user_id = ? and " .
            "(cal_event.name like '%" . $query . "%' or cal_event.description like '%" . $query . "%')";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $userId, $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            return $result;
        } else {
            return null;
        }
    }

    public static function getAllByCalendarId($calendarId) {
        $query = "select * " .
            "from cal_event " .
            "where cal_event.cal_calendar_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $calendarId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            return $result;
        } else
            return null;
    }

    public static function deleteReminders($eventId) {
        $query = "delete from " .
                "cal_event_reminder " .
                "where cal_event_reminder.cal_event_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $eventId);
        $stmt->execute();
    }

    public static function addReminder($eventId, $reminderTypeId, $reminderPeriodId, $value) {
        $query = "INSERT INTO cal_event_reminder(cal_event_id, cal_event_reminder_type_id, cal_event_reminder_period_id, value) VALUES (?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("iiii", $eventId, $reminderTypeId, $reminderPeriodId, $value);;
        $stmt->execute();
    }

    public static function getReminders($eventId) {
        $query = "select * " .
            "from cal_event_reminder " .
            "where cal_event_reminder.cal_event_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $eventId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            return $result;
        } else
            return null;
    }
}
