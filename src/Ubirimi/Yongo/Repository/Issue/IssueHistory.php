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
}