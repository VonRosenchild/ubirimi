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

class IssueComment
{

    public static function deleteById($commentId) {
        $query = 'delete from issue_comment where id = ? limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $commentId);
        $stmt->execute();
    }

    public function deleteByIssueId($issueId) {
        $query = 'DELETE FROM issue_comment WHERE issue_id = ?';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $issueId);
        $stmt->execute();
    }

    public function getById($commentId) {
        $query = 'select issue_comment.id, issue_comment.content, issue_comment.date_created, ' .
                 'general_user.first_name, general_user.last_name, general_user.id as user_id ' .
                 'from issue_comment ' .
                 'left join general_user on general_user.id = issue_comment.user_id ' .
                 'where issue_comment.id = ? ' .
                 'limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $commentId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return null;
    }

    public function getByIssueId($issueId, $order = false) {
        $query = 'SELECT issue_comment.id, user_id, content, issue_comment.date_created, ' .
            'general_user.id as user_id, general_user.first_name, general_user.last_name, general_user.avatar_picture, general_user.email ' .
            'FROM issue_comment ' .
            'LEFT join general_user on issue_comment.user_id = general_user.id ' .
            'WHERE issue_id = ? ';

        if ($order) {
            $query .= 'order by id ' . $order;
        } else {
            $query .= 'ORDER BY issue_comment.date_created ASC';
        }

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $issueId);
        $stmt->execute();

        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getByIssueIdAndUserId($issueId, $userId) {
        $query = 'SELECT issue_comment.id, user_id, content, issue_comment.date_created, ' .
            'general_user.id as user_id, general_user.first_name, general_user.last_name, general_user.avatar_picture ' .
            'FROM issue_comment ' .
            'LEFT join general_user on issue_comment.user_id = general_user.id ' .
            'WHERE issue_id = ? ' .
            'and user_id = ? ' .
            'order by issue_comment.id asc';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $issueId, $userId);
        $stmt->execute();

        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function updateById($commentId, $content, $userId, $date) {
        $query = 'update issue_comment set content = ?, user_id = ?, date_updated = ? where id = ? limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("sisi", $content, $userId, $date, $commentId);
        $stmt->execute();
    }

    public function add($issueId, $userId, $content, $date_created) {
        $query = "INSERT INTO issue_comment(issue_id, user_id, content, date_created) VALUES (?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("iiss", $issueId, $userId, $content, $date_created);
        $stmt->execute();
    }

    public function getByAssigneeFromHistoryAfterDate($issueId, $date) {
        $query = 'SELECT issue_comment.id, user_id, content, issue_comment.date_created ' .
            'from issue_history ' .
            'LEFT JOIN issue_comment on (issue_comment.issue_id = issue_history.issue_id and (issue_comment.user_id = issue_history.old_value_id or issue_comment.user_id = issue_history.new_value_id)) ' .
            'WHERE issue_history.issue_id = ? ' .
            "and issue_history.field = 'assignee' " .
            "and issue_comment.id is not null ";
        if ($date) {
            $query .= "and issue_comment.date_created >= ? ";
        }

        $query .= 'order by issue_comment.id asc';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        if ($date) {
            $stmt->bind_param("is", $issueId, $date);
        } else {
            $stmt->bind_param("i", $issueId);
        }

        $stmt->execute();

        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getByUserIdAfterDate($issueId, $userId, $date) {
        $query = 'SELECT issue_comment.id, user_id, content, issue_comment.date_created ' .
            'from issue_comment ' .
            'WHERE issue_comment.user_id = ? ' .
            "and issue_comment.issue_id = ? " .
            "and issue_comment.date_created >= ? " .
            "order by issue_comment.id asc";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("iis", $userId, $issueId, $date);
        $stmt->execute();

        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }
}
