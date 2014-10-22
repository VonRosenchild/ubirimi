<?php

namespace Ubirimi\Yongo\Repository\Issue;

use Ubirimi\Container\UbirimiContainer;

class Watcher
{
    public function add($issueId, $userId, $currentDate) {
        $query = "INSERT INTO yongo_issue_watch(yongo_issue_id, user_id, date_created) VALUES (?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("iis", $issueId, $userId, $currentDate);
        $stmt->execute();
    }

    public function getByIssueId($issueId) {
        $query = "SELECT user.id, user.first_name, user.last_name, user.email " .
            "FROM yongo_issue_watch " .
            "left join user on user.id = yongo_issue_watch.user_id " .
            "where yongo_issue_watch.yongo_issue_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $issueId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function deleteByUserIdAndIssueId($issueId, $userId) {
        $query = "delete from yongo_issue_watch where yongo_issue_id = ? and user_id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $issueId, $userId);
        $stmt->execute();
    }

    public function deleteByIssueId($issueId) {
        $query = "delete from yongo_issue_watch where yongo_issue_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $issueId);
        $stmt->execute();
    }
}
