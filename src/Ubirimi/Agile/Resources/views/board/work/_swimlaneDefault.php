<?php

use Ubirimi\Yongo\Repository\Issue\Issue;

$parameters = array('sprint' => $sprintId);

if ($onlyMyIssuesFlag) {
    $parameters['assignee'] = $loggedInUserId;
}
$strategyIssue = Issue::getByParameters($parameters, $loggedInUserId);

if ($strategyIssue) {
    require '_strategy_body.php';
    $index++;
}
