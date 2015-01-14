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

namespace Ubirimi\Yongo\Repository\Issue;

use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Util;

class WorkLog
{
    public function getByIssueId($issueId) {
        $query = 'select issue_work_log.id, issue_work_log.time_spent, issue_work_log.date_started, issue_work_log.comment, general_user.id as user_id, general_user.first_name, general_user.last_name, edited_flag
                  from issue_work_log
                  left join general_user on issue_work_log.user_id = general_user.id
                  where issue_work_log.issue_id = ?
                  order by issue_work_log.date_created desc';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $issueId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return false;
    }

    public function getById($Id) {
        $query = 'select *
                  from issue_work_log
                  where issue_work_log.id = ?
                  limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $Id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return false;
    }

    public function addLog($issueId, $loggedInUserId, $timeSpent, $dateStarted, $comment, $currentDate) {
        $query = "INSERT INTO issue_work_log(issue_id, user_id, time_spent, comment, date_started, date_created) VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("iissss", $issueId, $loggedInUserId, $timeSpent, $comment, $dateStarted, $currentDate);
        $stmt->execute();
    }

    public function updateLogById($workLogId, $timeSpent, $dateStartedString, $comment) {
        $query = "update issue_work_log set time_spent = ?, comment = ?, date_started = ?, edited_flag = 1 where id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("sssi", $timeSpent, $comment, $dateStartedString, $workLogId);
        $stmt->execute();
    }

    public function deleteById($Id) {
        $query = "delete from issue_work_log where id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $Id);
        $stmt->execute();
    }

    public function deleteByIssueId($issueId) {
        $query = 'delete from issue_work_log where issue_id = ?';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $issueId);
        $stmt->execute();
    }

    public function adjustRemainingEstimate($issueData, $timeSpent, $remainingTime, $hoursPerDay, $daysPerWeek, $loggedInUserId) {
        $issueRemainingTime = $issueData['remaining_estimate'];
        $issueRemainingMinutes = Util::transformLogTimeToMinutes($issueRemainingTime, $hoursPerDay, $daysPerWeek);

        if ($remainingTime == 'automatic') {
            $timeSpentMinutes = Util::transformLogTimeToMinutes($timeSpent, $hoursPerDay, $daysPerWeek);
            $difference = $issueRemainingMinutes - $timeSpentMinutes;

            if ($difference < 0) {
                $difference = 0;
            }
            $remainingTime = Util::transformTimeToString($difference, $hoursPerDay, $daysPerWeek);
            if ('' == $remainingTime) {
                $remainingTime = 0;
            }

        } else if ($remainingTime == 'existing') {
            $timeSpentMinutes = 0;
            $difference = $issueRemainingMinutes - $timeSpentMinutes;

            if ($difference < 0) {
                $difference = 0;
            }
            $remainingTime = Util::transformTimeToString($difference, $hoursPerDay, $daysPerWeek);
        } else if ($remainingTime == 'estimate_unset') {
            $remainingTime = null;
        } else if ($remainingTime[0] == '=') {
            $remainingTime = str_replace("=", '', $remainingTime);
            $remainingTimeMinutes = Util::transformLogTimeToMinutes($remainingTime, $hoursPerDay, $daysPerWeek);
            $difference = $remainingTimeMinutes;

            if ($difference < 0)
                $difference = 0;
            $remainingTime = Util::transformTimeToString($difference, $hoursPerDay, $daysPerWeek);

        } else if ($remainingTime[0] == "-") {
            $remainingTime = str_replace("-", '', $remainingTime);
            $remainingTimeMinutes = Util::transformLogTimeToMinutes($remainingTime, $hoursPerDay, $daysPerWeek);
            $difference = $issueRemainingMinutes - $remainingTimeMinutes;

            if ($difference < 0)
                $difference = 0;
            $remainingTime = Util::transformTimeToString($difference, $hoursPerDay, $daysPerWeek);
        } else if ($remainingTime[0] == "+") {
            $remainingTime = str_replace("+", '', $remainingTime);
            $remainingTimeMinutes = Util::transformLogTimeToMinutes($remainingTime, $hoursPerDay, $daysPerWeek);
            $sum = $issueRemainingMinutes + $remainingTimeMinutes;

            $remainingTime = Util::transformTimeToString($sum, $hoursPerDay, $daysPerWeek);
        }

        // transform it to string

        if ($remainingTime !== null) {
            $remainingTime = str_replace(array(" ", ','), '', $remainingTime);
            $remainingTime = str_replace(array('weeks', 'week'), 'w', $remainingTime);
            $remainingTime = str_replace(array('days', 'day'), 'd', $remainingTime);
            $remainingTime = str_replace(array('hours', 'hour'), 'h', $remainingTime);
            $remainingTime = str_replace(array('minutes', 'minutes'), 'm', $remainingTime);

            if ($remainingTime == "") {
                $remainingTime = 0;
            }

            WorkLog::updateRemainingEstimate($issueData['id'], $remainingTime);
        } else {
            WorkLog::clearRemainingEstimate($issueData['id']);
        }

        return $remainingTime;
    }

    public function updateRemainingEstimate($issueId, $remainingTime) {
        $query = 'update yongo_issue SET remaining_estimate = ? where id = ? limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("si", $remainingTime, $issueId);
        $stmt->execute();
    }

    public function clearRemainingEstimate($issueId) {
        $query = 'update yongo_issue SET remaining_estimate = NULL where id = ? limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $issueId);
        $stmt->execute();
    }
}
