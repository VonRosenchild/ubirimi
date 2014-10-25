<?php

use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Yongo\Repository\Issue\Issue;

$parameters = array('sprint' => $sprintId);

if ($onlyMyIssuesFlag) {
    $parameters['assignee'] = $loggedInUserId;
}
$strategyIssue = UbirimiContainer::get()['repository']->get(Issue::class)->getByParameters($parameters, $loggedInUserId);

if ($strategyIssue) {
    require '_strategy_body.php';
    $index++;
}
