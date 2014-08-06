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
        $query = 'select * from issue_history where issue_id = ? and new_value_id = ? ';
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

    public static function updateChangedIds($Id, $oldValueId, $newValueiD) {
        $queryUpdate = 'update issue_history set old_value_id = ?, new_value_id = ? where id = ? limit 1';

        if ($stmtUpdate = UbirimiContainer::get()['db.connection']->prepare($queryUpdate)) {

            $stmtUpdate->bind_param("ssi", $oldValueId, $newValueiD, $Id);
            $stmtUpdate->execute();
        }
    }
}