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

    public static function updateChangedIds($Id, $oldValueId, $newValueiD) {
        $queryUpdate = 'update issue_history set old_value_id = ?, new_value_id = ? where id = ? limit 1';

        if ($stmtUpdate = UbirimiContainer::get()['db.connection']->prepare($queryUpdate)) {

            $stmtUpdate->bind_param("ssi", $oldValueId, $newValueiD, $Id);
            $stmtUpdate->execute();
        }
    }
}