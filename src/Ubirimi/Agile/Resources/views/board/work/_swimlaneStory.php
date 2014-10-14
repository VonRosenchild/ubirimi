<?php



use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Yongo\Repository\Issue\Issue;

// get issues that have children. Those are shown first
$allSprintIssuesWithChildren = Sprint::getIssuesBySprintIdWithChildren($sprintId, null, $loggedInUserId);
$parentChildrenIssueIds = array();

while ($allSprintIssuesWithChildren && $issue = $allSprintIssuesWithChildren->fetch_array(MYSQLI_ASSOC)) {
    $parameters = array('parent_id' => $issue['id']);
    if ($onlyMyIssuesFlag) {
        $parameters['assignee'] = $loggedInUserId;
    }
    $strategyIssue = UbirimiContainer::getRepository('yongo.issue.issue')->getByParameters($parameters, $loggedInUserId);
    $parentChildrenIssueIds[] = $issue['id'];

    if ($strategyIssue) {

        require '_strategy_body.php';
        $index++;

//            echo '<table width="100%" cellpadding="0px" cellspacing="0px" border="0" class="agile_work_' . $index . '">';
//            require '_parent_story_box.php';
//            AgileBoard::renderIssues($childrenIssue, $columns, $index);
//            echo '</table>';
        $index++;
    }
}

$allSprintIssuesWithoutChildren = Sprint::getIssuesBySprintIdWithoutChildren($sprintId, null, $loggedInUserId, $parentChildrenIssueIds);

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

    echo '<table width="100%" cellpadding="0" cellspacing="0px" border="0" class="agile_work_' . $index . '">';
    $this->getRepository('agile.board.board')->renderIssues($allSprintIssuesWithoutChildren, $columns, $index);
    echo '</table>';
}