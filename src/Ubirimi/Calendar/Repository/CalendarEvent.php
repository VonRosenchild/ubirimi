<?php

namespace Ubirimi\Calendar\Repository;

use Ubirimi\Container\UbirimiContainer;

class CalendarEvent
{
    public static function add($calendarId, $userCreatedId, $name, $description, $location, $start, $end, $color, $currentDate, $repeatData = null, $clientSettings) {
        $calEventRepeatId = null;
        $repeatDates = array();
        $repeatDay = array(0, 0, 0, 0, 0, 0, 0);
        $endAfterOccurrences = null;

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
                $repeatEndOnDate = null;

                if ('o' == $endData[0]) {
                    $repeatEndOnDate = substr($endData, 1);
                }

                if ('n' == $endData[0] || 'o' == $endData[0]) {
                    $dateTemporary = date_create($repeatStartDate, new \DateTimeZone($clientSettings['timezone']));
                    date_add($dateTemporary, date_interval_create_from_date_string('30 years'));

                    $repeatEndDate = date_format($dateTemporary, 'Y-m-d');
                    $repeatEndDateTemporary = date_create($repeatStartDate);

                    while (date_format($repeatEndDateTemporary, 'Y-m-d') <= $repeatEndDate) {

                        date_add($repeatEndDateTemporary, date_interval_create_from_date_string(intval($repeatEvery) . ' days'));
                        $offsetEndDate = clone $repeatEndDateTemporary;
                        date_add($offsetEndDate, date_interval_create_from_date_string($daysBetween . ' days'));

                        if ($repeatEndOnDate && date_format($offsetEndDate, 'Y-m-d') > $repeatEndOnDate) {
                            break;
                        }
                        $repeatDates[] = array(date_format($repeatEndDateTemporary, 'Y-m-d'), date_format($offsetEndDate, 'Y-m-d'));
                    }
                } else if ('a' == $endData[0]) {

                    $pos = 1;
                    $endAfterOccurrences = intval($endData[1]);
                    $repeatEndDate = $repeatStartDate;
                    while ($pos < $endAfterOccurrences) {
                        $repeatEndDate = date('Y-m-d', strtotime("+" . intval($repeatEvery) . ' days', strtotime($repeatEndDate)));

                        $repeatDates[] = array($repeatEndDate, date('Y-m-d', strtotime("+" . $daysBetween . ' days', strtotime($repeatEndDate))));
                        $pos++;
                    }
                }

            } else if (CalendarEventRepeatCycle::REPEAT_WEEKLY == $repeatType) {
                // $repeatData format
                // repeatType#repeat_every#n|#a3|#o2013-08-08#start_date#0#1#1#1#1#1#0

                $repeatEvery = $repeatDataArray[1];
                $endData = $repeatDataArray[2];
                $repeatStartDate = $repeatDataArray[3];
                $repeatEndDate = null;
                $repeatEndOnDate = null;

                $repeatDay[0] = $repeatDataArray[4];
                $repeatDay[1] = $repeatDataArray[5];
                $repeatDay[2] = $repeatDataArray[6];
                $repeatDay[3] = $repeatDataArray[7];
                $repeatDay[4] = $repeatDataArray[8];
                $repeatDay[5] = $repeatDataArray[9];
                $repeatDay[6] = $repeatDataArray[10];

                if (($repeatDay[0] + $repeatDay[1] + $repeatDay[2] + $repeatDay[3] + $repeatDay[4] + $repeatDay[5] + $repeatDay[6]) == 0) {
                    $dateTemporary = date_create($repeatStartDate);
                    $repeatDay[date("w", $dateTemporary->getTimestamp()) - 1] = 1;
                }

                if ('n' == $endData[0]) {
                    $endAfterOccurrences = 10000;
                } else if ('a' == $endData[0]) {
                    $endAfterOccurrences = $endData[1];
                } else if ('o' == $endData[0]) {
                    $repeatEndOnDate = substr($endData, 1);
                    if (10 == strlen($repeatEndOnDate)) {
                        $repeatEndOnDate .= ' 00:00:00';
                    }
                    $endAfterOccurrences = 10000;
                }

                $pos = 1;
                $repeatEndDate = $repeatStartDate;
                $repeatDates[] = array($start . ':00', $end . ':00');

                $repeatEveryDays = $repeatEvery * 7;

                while ($pos < intval($endAfterOccurrences)) {
                    $repeatEndDate = new \DateTime($repeatEndDate, new \DateTimeZone($clientSettings['timezone']));

                    date_add($repeatEndDate, date_interval_create_from_date_string('1 days'));

                    $lastDate = new \DateTime(end($repeatDates)[0], new \DateTimeZone($clientSettings['timezone']));

                    $repeatEndDateClone = clone $repeatEndDate;

                    $sameWeek = (date_format($repeatEndDate, "W") === date_format($lastDate, "W"));

                    $found = false;
                    date_sub($repeatEndDateClone, date_interval_create_from_date_string($repeatEveryDays . ' days'));
                    for ($i = count($repeatDates) - 1; $i >= 0; $i--) {
                        if ($repeatEndDateClone->format('Y-m-d H:i:s') == $repeatDates[$i][0]) {
                            $found = true;
                            break;
                        }
                    }

                    if ($sameWeek || $found) {
                        if ($repeatDay[date_format($repeatEndDate, "w")]) {
                            if ($repeatEndOnDate && date_format($repeatEndDate, 'Y-m-d H:i:s') > $repeatEndOnDate) {
                                break;
                            }
                            $endDateTemporary = new \DateTime(date_format($repeatEndDate, 'Y-m-d H:i:s'), new \DateTimeZone($clientSettings['timezone']));
                            date_add($endDateTemporary, date_interval_create_from_date_string($daysBetween . ' days'));
                            $repeatDates[] = array(date_format($repeatEndDate, 'Y-m-d H:i:s'), date_format($endDateTemporary, 'Y-m-d H:i:s'));
                            $pos++;
                        }
                    }
                    if (substr(end($repeatDates)[1], 0, 4) - substr($repeatDates[0][0], 0, 4) >= 30) {
                        break;
                    }
                    $repeatEndDate = date_format($repeatEndDate, 'Y-m-d');
                }
                $repeatDates[0] = null;
            }

            $query = "INSERT INTO cal_event_repeat(cal_event_repeat_cycle_id, repeat_every, end_after_occurrences, start_date, end_date, on_day_0, on_day_1, on_day_2, on_day_3, on_day_4, on_day_5, on_day_6) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

            $day0 = $repeatDay[0];
            $day1 = $repeatDay[1];
            $day2 = $repeatDay[2];
            $day3 = $repeatDay[3];
            $day4 = $repeatDay[4];
            $day5 = $repeatDay[5];
            $day6 = $repeatDay[6];

            if (10000 == $endAfterOccurrences) {
                $endAfterOccurrences = null;
            }
            var_dump($repeatEndOnDate);
            $stmt->bind_param("iiissiiiiiii", $repeatType, $repeatEvery, $endAfterOccurrences, $repeatStartDate, $repeatEndOnDate, $day0, $day1, $day2, $day3, $day4, $day5, $day6);
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

        if (count($repeatDates)) {

            // update the cal_event_link_id
            $query = "update cal_event set cal_event_link_id = ? where id = ? limit 1";
            $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

            $stmt->bind_param("ii", $eventId, $eventId);
            $stmt->execute();

            $queryMain = "INSERT INTO cal_event(cal_calendar_id, user_created_id, cal_event_link_id, cal_event_repeat_id, name, description, location, date_from, " .
                "date_to, color, date_created) VALUES ";
            $separator = '';

            $query = $queryMain;

            for ($k = 0; $k < count($repeatDates); $k++) {

                if (isset($repeatDates[$k])) {
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

                    if ($k % 1000 == 0) {
                        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
                        $stmt->execute();
                        $query = $queryMain;
                        $separator = '';
                    }
                }
            }
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
            "cal_event.cal_event_link_id, cal_event.cal_event_repeat_id, " .
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
        } else {
            return null;
        }
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
                // todo: delete shares and reminders also
                $query = "delete from cal_event where id >= ? and cal_event_link_id = ?";
                $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
                $stmt->bind_param("ii", $eventId, $event['cal_event_link_id']);
                $stmt->execute();

                break;

            case 'all_series':
                $event = CalendarEvent::getById($eventId, 'array');
                // todo: delete shares and reminders also
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

    public static function getRepeatDataById($id) {
        $query = "select * " .
            "from cal_event_repeat " .
            "where id = ? " .
            "limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            return $result->fetch_array(MYSQLI_ASSOC);
        } else
            return null;
    }

    public static function updateRemoveLinkAndRepeat($eventId, $dateFrom, $dateTo) {
        $query = "update cal_event set cal_event_repeat_id = NULL, cal_event_link_id = NULL, date_from = ?, date_to = ? where id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ssi", $dateFrom, $dateTo, $eventId);
        $stmt->execute();
    }

    public static function deleteEventAndFollowingByLinkId($eventId) {
        $event = CalendarEvent::getById($eventId, 'array');
        $linkId = $event['cal_event_link_id'];

        $query = "delete from " .
            "cal_event " .
            "where cal_event.cal_event_link_id = ? and id >= ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $linkId, $eventId);
        $stmt->execute();
    }
}