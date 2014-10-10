<?php

use Ubirimi\Container\UbirimiContainer;

$parameters = array('sprint' => $sprintId);

if ($onlyMyIssuesFlag) {
    $parameters['assignee'] = $loggedInUserId;
}
$strategyIssue = UbirimiContainer::getRepository('yongo.issue.issue')->getByParameters($parameters, $loggedInUserId);

if ($strategyIssue) {
    require '_strategy_body.php';
    $index++;
}
