<?php

        use Ubirimi\Agile\Repository\AgileBoard;
    use Ubirimi\Yongo\Repository\Issue\Issue;

    echo '<table width="100%" cellpadding="0" cellspacing="0px" border="0" class="agile_work_' . $index . '">';

        $parameters = array('sprint' => $sprintId);

        if ($onlyMyIssuesFlag) {
            $parameters['assignee'] = $loggedInUserId;
        }
        $allSprintIssues = Issue::getByParameters($parameters, $loggedInUserId);

        AgileBoard::renderIssues($allSprintIssues, $columns, $index);
    echo '</table>';
