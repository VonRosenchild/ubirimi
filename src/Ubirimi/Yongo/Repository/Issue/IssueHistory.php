<?php

namespace Ubirimi\Yongo\Repository\Issue;

use Ubirimi\Container\UbirimiContainer;

class IssueHistory
{
    public static function deleteByIssueId($issueId) {
        $query = 'DELETE FROM issue_history WHERE issue_id = ?';
        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $issueId);
        $stmt->execute();
    }

    public static function getAll() {
        $query = 'select * from issue_history';
        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result;
    }
    public static function getByAssigneeNewChangedAfterDate($issueId, $userAssigneeId, $date) {
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

    public static function updateChangedIds($Id, $oldValueId, $newValueId) {
        $queryUpdate = 'update issue_history set old_value_id = ?, new_value_id = ? where id = ? limit 1';

        if ($stmtUpdate = UbirimiContainer::get()['db.connection']->prepare($queryUpdate)) {

            $stmtUpdate->bind_param("ssi", $oldValueId, $newValueId, $Id);
            $stmtUpdate->execute();
        }
    }

    public static function getByIssueIdAndUserId($issueId = null, $userId = null, $order = null)
    {

        $query = '(select \'history_event\' as source, ' .
            'issue_history.date_created, ' .
            'issue_history.field as field, ' .
            'issue_history.old_value as old_value, ' .
            'issue_history.new_value as new_value, ' .
            'null as content, ' .
            'user.id as user_id, user.first_name, user.last_name, ' .
            'yongo_issue.nr as nr, ' .
            'project.code as code, ' .
            'yongo_issue.id as issue_id ' .
            'from issue_history ' .
            'left join user on user.id = issue_history.by_user_id ' .
            'left join yongo_issue on yongo_issue.id = issue_history.issue_id ' .
            'left join project on project.id = yongo_issue.project_id ' .
            'where ';

        if ($issueId) $query .= ' issue_history.issue_id = ' . $issueId . ' ';
        if ($userId) $query .= ' issue_history.by_user_id = ' . $userId . ' ';

        if (!$order) {
            $order = 'desc';
        }
        $query .= 'order by date_created ' . $order . ', user_id) ';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }
}