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

class IssueHistory
{
    public function deleteByIssueId($issueId) {
        $query = 'DELETE FROM issue_history WHERE issue_id = ?';
        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $issueId);
        $stmt->execute();
    }

    public function getAll() {
        $query = 'select * from issue_history';
        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result;
    }
    public function getByAssigneeNewChangedAfterDate($issueId, $userAssigneeId, $date) {
        $query = "select * from issue_history where issue_id = ? and new_value_id = ? and field = 'assignee' ";
        if ($date) {
            $query .= ' and date_created >= ? ';
        }
        $query .= ' order by id asc ';
        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        if ($date) {
            $stmt->bind_param("iis", $issueId, $userAssigneeId, $date);
        } else {
            $stmt->bind_param("ii", $issueId, $userAssigneeId);
        }

        $stmt->execute();

        $result = $stmt->get_result();
        return $result;
    }

    public function updateChangedIds($Id, $oldValueId, $newValueId) {
        $queryUpdate = 'update issue_history set old_value_id = ?, new_value_id = ? where id = ? limit 1';

        if ($stmtUpdate = UbirimiContainer::get()['db.connection']->prepare($queryUpdate)) {

            $stmtUpdate->bind_param("ssi", $oldValueId, $newValueId, $Id);
            $stmtUpdate->execute();
        }
    }

    public function getByIssueIdAndUserId($issueId = null, $userId = null, $order = null, $resultType = null)
    {

        $query = '(select \'history_event\' as source, ' .
            'issue_history.date_created, ' .
            'issue_history.field as field, ' .
            'issue_history.old_value as old_value, ' .
            'issue_history.new_value as new_value, ' .
            'issue_history.old_value_id as old_value_id, ' .
            'issue_history.new_value_id as new_value_id, ' .
            'null as content, ' .
            'general_user.id as user_id, general_user.first_name, general_user.last_name, ' .
            'yongo_issue.nr as nr, ' .
            'project.code as code, ' .
            'yongo_issue.id as issue_id ' .
            'from issue_history ' .
            'left join general_user on general_user.id = issue_history.by_user_id ' .
            'left join yongo_issue on yongo_issue.id = issue_history.issue_id ' .
            'left join project on project.id = yongo_issue.project_id ' .
            'where ';

        if ($issueId) $query .= ' issue_history.issue_id = ' . $issueId . ' ';
        if ($userId) $query .= ' issue_history.by_user_id = ' . $userId . ' ';

        if (!$order) {
            $order = 'desc';
        }
        $query .= 'order by date_created ' . $order . ', user_id) ';

        $query .= ' UNION (select ' .
        "'event_commented' as source, " .
        'issue_comment.date_created as date_created, ' .
        'null as field, ' .
        'null as old_value, ' .
        'null as new_value, ' .
        'null as old_value_id, ' .
        'null as new_value_id, ' .
        'null as content, ' .
        'general_user.id as user_id, general_user.first_name, general_user.last_name, ' .
        'yongo_issue.nr as nr, ' .
        'project.code as code, ' .
        'yongo_issue.id as issue_id ' .
        'from yongo_issue ' .
        'left join issue_comment on yongo_issue.id = issue_comment.issue_id ' .
        'left join general_user on general_user.id = issue_comment.user_id ' .
        'left join project on project.id = yongo_issue.project_id ' .
        'where yongo_issue.id = ' . $issueId . ' ' .
        'and issue_comment.issue_id is not null ' .

        'order by date_created ' . $order . ', user_id) ';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            if ($resultType == 'array') {
                $resultArray = array();
                while ($data = $result->fetch_array(MYSQLI_ASSOC)) {
                    $resultArray[] = $data;
                }
                return $resultArray;
            } else return $result;

        } else {
            return null;
        }
    }
}