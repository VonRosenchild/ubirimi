<?php

namespace Ubirimi\Yongo\Repository\Issue;

use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\Entity;

class Comment extends Entity
{

    function __construct() {
        $this->tableName = 'issue_comment';
    }

    public function deleteByIssueId($issueId) {
        $query = 'DELETE FROM issue_comment WHERE issue_id = ?';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $issueId);
        $stmt->execute();
    }

    public function getById($commentId) {
        $query = 'select issue_comment.id, issue_comment.content, issue_comment.date_created, ' .
                 'user.first_name, user.last_name, user.id as user_id ' .
                 'from issue_comment ' .
                 'left join user on user.id = issue_comment.user_id ' .
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
            'user.id as user_id, user.first_name, user.last_name, user.avatar_picture, user.email ' .
            'FROM issue_comment ' .
            'LEFT JOIN user on issue_comment.user_id = user.id ' .
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
            'user.id as user_id, user.first_name, user.last_name, user.avatar_picture ' .
            'FROM issue_comment ' .
            'LEFT JOIN user on issue_comment.user_id = user.id ' .
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
