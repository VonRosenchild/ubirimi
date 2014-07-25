<?php

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

    public static function deleteByIssueId($issueId) {
        $query = 'DELETE FROM issue_comment WHERE issue_id = ?';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $issueId);
        $stmt->execute();
    }

    public static function getById($commentId) {
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

    public static function getByIssueId($issueId, $order = false) {
        $query = 'SELECT issue_comment.id, user_id, content, issue_comment.date_created, ' .
            'user.id as user_id, user.first_name, user.last_name, user.avatar_picture ' .
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

    public static function updateById($commentId, $content, $userId, $date) {
        $query = 'update issue_comment set content = ?, user_id = ?, date_updated = ? where id = ? limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("sisi", $content, $userId, $date, $commentId);
        $stmt->execute();
    }

    public static function add($issueId, $userId, $content, $date_created) {
        $query = "INSERT INTO issue_comment(issue_id, user_id, content, date_created) VALUES (?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("iiss", $issueId, $userId, $content, $date_created);
        $stmt->execute();
    }
}
