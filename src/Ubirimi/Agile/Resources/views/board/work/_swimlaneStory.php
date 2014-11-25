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

use Ubirimi\Agile\Repository\Board\Board;
use Ubirimi\Agile\Repository\Sprint\Sprint;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Yongo\Repository\Issue\Issue;

// get issues that have children. Those are shown first
$allSprintIssuesWithChildren = UbirimiContainer::get()['repository']->get(Sprint::class)->getIssuesBySprintIdWithChildren($sprintId, null, $loggedInUserId);
$parentChildrenIssueIds = array();

while ($allSprintIssuesWithChildren && $issue = $allSprintIssuesWithChildren->fetch_array(MYSQLI_ASSOC)) {
    $parameters = array('parent_id' => $issue['id']);
    if ($onlyMyIssuesFlag) {
        $parameters['assignee'] = $loggedInUserId;
    }
    $strategyIssue = UbirimiContainer::get()['repository']->get(Issue::class)->getByParameters($parameters, $loggedInUserId);
    $parentChildrenIssueIds[] = $issue['id'];

    if ($strategyIssue) {

        require '_strategy_body.php';
        $index++;
    }
}

$allSprintIssuesWithoutChildren = UbirimiContainer::get()['repository']->get(Sprint::class)->getIssuesBySprintIdWithoutChildren($sprintId, null, $loggedInUserId, $parentChildrenIssueIds);

if ($allSprintIssuesWithoutChildren) {
    if ($allSprintIssuesWithChildren) {
        echo '<table width="100%" cellpadding="0" cellspacing="0px" border="0" class="agile_work_' . $index++ . '">';
            echo '<tr>';
                echo '<td colspan="' . (count($columns) + (count($columns) - 1)) . '">';
                    echo '<div style="position: inherit; background-color: #f1f1f1; padding: 3px; border: 1px solid #acacac">';
                        echo '<img border="0" src="/img/br_down.png" style="padding-bottom: 2px;"/> ';
                        echo '<span>Other Issues</span>';
                    echo '</div>';
                echo '</td>';
            echo '</tr>';
        echo '</table>';
    }

    $strategyIssue = $allSprintIssuesWithoutChildren;
    require '_strategy_body.php';
}