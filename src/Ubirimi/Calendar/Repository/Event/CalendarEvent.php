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

namespace Ubirimi\Calendar\Repository\Event;

use Ubirimi\Calendar\Repository\Reminder\RepeatCycle;
use Ubirimi\Container\UbirimiContainer;

class CalendarEvent
{
    public function add($calendarId, $userCreatedId, $name, $description, $location, $start, $end, $color, $currentDate, $repeatData = null, $clientSettings) {
        $calEventRepeatId = null;
        $repeatDates = array();
        $repeatDay = array(0, 0, 0, 0, 0, 0, 0);
        $endAfterOccurrences = null;

        if ($repeatData) {
            $repeatDataArray = explode("#", $repeatData);
            $repeatType = $repeatDataArray[0];

            $daysBetween = (strtotime($end) - strtotime($start)) / 86400;

            if (RepeatCycle::REPEAT_DAILY == $repeatType) {

                // $repeatData format: repeatType#repeat_every#n|#a3|#o2013-08-08#start_date

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
                    $endAfterOccurrences = intval(substr($endData, 1));
                    $repeatEndDate = $repeatStartDate;
                    while ($pos < $endAfterOccurrences) {
                        $repeatEndDate = date('Y-m-d', strtotime("+" . intval($repeatEvery) . ' days', strtotime($repeatEndDate)));

                        $repeatDates[] = array($repeatEndDate, date('Y-m-d', strtotime("+" . $daysBetween . ' days', strtotime($repeatEndDate))));
                        $pos++;
                    }
                }

            } else if (RepeatCycle::REPEAT_WEEKLY == $repeatType) {
                // $repeatData format: repeatType#repeat_every#n|#a3|#o2013-08-08#start_date#0#1#1#1#1#1#0

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

                $lastDayInWeek = null;
                for ($i = 6; $i >= 0; $i--) {
                    if ($repeatDay[$i] == 1) {
                        $lastDayInWeek = $i;
                        break;
                    }
                }

                if (($repeatDay[0] + $repeatDay[1] + $repeatDay[2] + $repeatDay[3] + $repeatDay[4] + $repeatDay[5] + $repeatDay[6]) == 0) {
                    $dateTemporary = date_create($repeatStartDate);
                    $repeatDay[date("w", $dateTemporary->getTimestamp()) - 1] = 1;
                }
                $daysInWeekPresent = $repeatDay[0] + $repeatDay[1] + $repeatDay[2] + $repeatDay[3] + $repeatDay[4] + $repeatDay[5] + $repeatDay[6];

                if ('n' == $endData[0]) {
                    $endAfterOccurrences = 10000;
                } else if ('a' == $endData[0]) {
                    $endAfterOccurrences = substr($endData, 1);
                } else if ('o' == $endData[0]) {
                    $repeatEndOnDate = substr($endData, 1);
                    if (10 == strlen($repeatEndOnDate)) {
                        $repeatEndOnDate .= ' 00:00:00';
                    }
                    $endAfterOccurrences = 10000;
                }

                $repeatEndDate = $repeatStartDate;
                $repeatDates[] = array($start . ':00', $end . ':00');

                $repeatEveryDays = $repeatEvery * 7;

                $yearsDiff = substr(end($repeatDates)[1], 0, 4) - substr($repeatDates[0][0], 0, 4);
                $maxLimitExceeded = ($repeatEndOnDate && date_format($repeatEndDate, 'Y-m-d H:i:s') > $repeatEndOnDate);

                while ($yearsDiff < 30 && !$maxLimitExceeded) {

                    $repeatEndDate = new \DateTime($repeatEndDate, new \DateTimeZone($clientSettings['timezone']));
                    $repeatEndDateClone = clone $repeatEndDate;

                    date_add($repeatEndDate, date_interval_create_from_date_string('1 days'));

                    if ($repeatDay[date_format($repeatEndDate, "w")] === "1") {

                        $endDateTemporary = new \DateTime(date_format($repeatEndDate, 'Y-m-d H:i:s'), new \DateTimeZone($clientSettings['timezone']));
                        date_add($endDateTemporary, date_interval_create_from_date_string($daysBetween . ' days'));
                        $repeatDates[] = array(date_format($repeatEndDate, 'Y-m-d H:i:s'), date_format($endDateTemporary, 'Y-m-d H:i:s'));
                    }

                    $yearsDiff = substr(end($repeatDates)[1], 0, 4) - substr($repeatDates[0][0], 0, 4);
                    $maxLimitExceeded = ($repeatEndOnDate && date_format($repeatEndDate, 'Y-m-d H:i:s') > $repeatEndOnDate);

                    if ($lastDayInWeek == date_format($repeatEndDate, "w")) {
                        $repeatEndDateClone = $repeatDates[count($repeatDates) - $daysInWeekPresent][0];
                        $repeatEndDateClone = new \DateTime($repeatEndDateClone, new \DateTimeZone($clientSettings['timezone']));
                        date_add($repeatEndDateClone, date_interval_create_from_date_string(($repeatEveryDays - 1) . ' days'));
                    } else {
                        date_add($repeatEndDateClone, date_interval_create_from_date_string('1 days'));
                    }
                    $repeatEndDate = date_format($repeatEndDateClone, 'Y-m-d');
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
            $stmt->bind_param("iiissiiiiiii", $repeatType, $repeatEvery, $endAfterOccurrences, $repeatStartDate, $repeatEndOnDate, $day0, $day1, $day2, $day3, $day4, $day5, $day6);
            $stmt->execute();

            $calEventRepeatId = UbirimiContainer::get()['db.connection']->insert_id;
        }

        $query = "INSERT INTO cal_event(cal_calendar_id, user_created_id, cal_event_repeat_id, name, description, location, date_from, " .
                 "date_to, color, date_created) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

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

            $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

            $stmt->execute();
        }

        return $eventId;
    }

    public function getByCalendarId($calendarId, $filterStartDate, $filterEndDate, $defaultCalendarSelected = null, $userId = null, $resultType = null) {
        $query = "SELECT cal_event.id, cal_event.user_created_id, cal_event.date_from, cal_event.date_to, cal_event.name, cal_event.description, cal_event.color, cal_event.location, " .
            "cal_calendar.name as calendar_name, TIMESTAMPDIFF(SECOND, cal_event.date_from, cal_event.date_to) as timediff, cal_calendar.color as calendar_color, " .
            "cal_event.cal_event_repeat_id, cal_event.cal_event_link_id, cal_calendar.id as calendar_id " .
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
                "cal_calendar.name as calendar_name, TIMESTAMPDIFF(SECOND, cal_event.date_from, cal_event.date_to) as timediff, cal_calendar.color as calendar_color, " .
                "cal_event.cal_event_repeat_id, cal_event.cal_event_link_id, cal_calendar.id as calendar_id " .
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

    public function getByCalendarIds($calendarIds, $filterStartDate, $filterEndDate, $resultType = null) {
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

    public function getById($eventId, $resultType = null) {
        $query = "SELECT cal_event.id, cal_event.user_created_id, cal_event.date_from, cal_event.date_to, cal_event.name, cal_event.description, cal_event.color, cal_event.location, " .
            "cal_event.cal_event_link_id, cal_event.cal_event_repeat_id, " .
            "cal_calendar.name as calendar_name, cal_calendar.id as calendar_id, " .
            "general_user.client_id " .
            "FROM cal_event " .
            "left join cal_calendar on cal_calendar.id = cal_event.cal_calendar_id " .
            "left join general_user on general_user.id = cal_calendar.user_id " .
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

    public function updateById($eventId, $calendarId, $name, $description, $location, $dateFrom, $dateTo, $color, $dateUpdated) {
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

    public function shareWithUsers($eventId, $userIds, $date) {
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

    public function getShareByUserIdAndEventId($userId, $eventId) {
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

    public function deleteEventSharesByUserId($eventId, $userId) {
        $query = "delete from cal_event_share where cal_event_id = ? and user_id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $eventId, $userId);
        $stmt->execute();
    }

    public function deleteAllEventShares($eventId) {
        $query = "delete from cal_event_share where cal_event_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $eventId);
        $stmt->execute();
    }

    public function deleteById($eventId, $recurringType = null) {
        // delete the shares
        UbirimiContainer::get()['repository']->get(CalendarEvent::class)->deleteAllEventShares($eventId);

        // delete the reminders
        UbirimiContainer::get()['repository']->get(CalendarEvent::class)->deleteReminders($eventId);

        switch ($recurringType) {
            case 'all_following':
                $event = UbirimiContainer::get()['repository']->get(CalendarEvent::class)->getById($eventId, 'array');
                // todo: delete shares and reminders also
                $query = "delete from cal_event where id >= ? and cal_event_link_id = ?";
                $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
                $stmt->bind_param("ii", $eventId, $event['cal_event_link_id']);
                $stmt->execute();

                break;

            case 'all_series':
                $event = UbirimiContainer::get()['repository']->get(CalendarEvent::class)->getById($eventId, 'array');
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

    public function getGuests($eventId) {
        $query = "select general_user.* " .
            "from cal_event_share " .
            "left join general_user on general_user.id = cal_event_share.user_id " .
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

    public function getByText($userId, $query) {
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

    public function getAllByCalendarId($calendarId) {
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

    public function deleteReminders($eventId) {
        $query = "delete from " .
                "cal_event_reminder " .
                "where cal_event_reminder.cal_event_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $eventId);
        $stmt->execute();
    }

    public function addReminder($eventId, $reminderTypeId, $reminderPeriodId, $value) {
        $query = "INSERT INTO cal_event_reminder(cal_event_id, cal_event_reminder_type_id, cal_event_reminder_period_id, value) VALUES (?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("iiii", $eventId, $reminderTypeId, $reminderPeriodId, $value);;
        $stmt->execute();
    }

    public function getReminders($eventId) {
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

    public function getRepeatDataById($id) {
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

    public function updateRemoveLinkAndRepeat($eventId, $dateFrom, $dateTo) {
        $query = "update cal_event set cal_event_repeat_id = NULL, cal_event_link_id = NULL, date_from = ?, date_to = ? where id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ssi", $dateFrom, $dateTo, $eventId);
        $stmt->execute();
    }

    public function deleteEventAndFollowingByLinkId($eventId) {
        $event = UbirimiContainer::get()['repository']->get(CalendarEvent::class)->getById($eventId, 'array');
        $linkId = $event['cal_event_link_id'];

        $query = "delete from " .
            "cal_event " .
            "where cal_event.cal_event_link_id = ? and id >= ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $linkId, $eventId);
        $stmt->execute();
    }
}