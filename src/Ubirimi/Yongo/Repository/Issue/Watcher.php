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

class Watcher
{
    public function add($issueId, $userId, $currentDate) {
        $query = "INSERT INTO yongo_issue_watch(yongo_issue_id, user_id, date_created) VALUES (?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("iis", $issueId, $userId, $currentDate);
        $stmt->execute();
    }

    public function getByIssueId($issueId) {
        $query = "select general_user.id, general_user.first_name, general_user.last_name, general_user.email " .
            "FROM yongo_issue_watch " .
            "left join general_user on general_user.id = yongo_issue_watch.user_id " .
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
