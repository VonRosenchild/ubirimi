<?php

use Ubirimi\Agile\Repository\Sprint\Sprint;
use Ubirimi\Container\UbirimiContainer;

// get all the assignees of the issues in this sprint
$allAssignees = UbirimiContainer::get()['repository']->get('agile.sprint.sprint')->getAssigneesBySprintId($sprintId);

while ($allAssignees && $user = $allAssignees->fetch_array(MYSQLI_ASSOC)) {

    $assignee = $user['id'];
    if ($onlyMyIssuesFlag && $assignee != $loggedInUserId) {
        $issuesOfAssignee = null;
    } else {
        $queryParameters = array('assignee' => $assignee,
                                 'sprint' => $sprintId,
                                 'sort' => 'sprint');
        $strategyIssue = UbirimiContainer::get()['repository']->get('yongo.issue.issue')->getByParameters($queryParameters, $loggedInUserId);
    }
    if ($strategyIssue) {

        require '_strategy_body.php';
        $index++;
    }
}

$allUnassignedIssues = null;
if (!$onlyMyIssuesFlag) {
    $allUnassignedIssues = UbirimiContainer::get()['repository']->get('yongo.issue.issue')->getByParameters(array('assignee' => 0, 'sprint' => $sprintId, 'sort' => 'sprint'), $loggedInUserId);
}

if ($allUnassignedIssues) {

    echo '<table width="100%" cellpadding="0" cellspacing="0px" border="0" class="agile_work_' . $index++ . '">';
        echo '<tr>';
            echo '<td colspan="' . (count($columns) + (count($columns) - 1)) . '">';
                echo '<div style="position: inherit; background-color: #f1f1f1; padding: 3px; border: 1px solid #acacac">';
                    echo '<img border="0" src="/img/br_down.png" style="padding-bottom: 2px;"/>';
                    echo 'Unassigned ' . $allUnassignedIssues->num_rows . ' issues';
                echo '</div>';
            echo '</td>';
        echo '</tr>';
    echo '</table>';
    echo '<table width="100%" cellpadding="0" cellspacing="0px" border="0" class="agile_work_' . $index . '">';
        UbirimiContainer::get()['repository']->get('agile.board.board')->renderIssues($allUnassignedIssues, $columns, $index, 'assignee');
    echo '</table>';
}