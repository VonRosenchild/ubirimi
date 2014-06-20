<?php

use Ubirimi\Yongo\Repository\Issue\IssueSettings;
use Ubirimi\Util;

function installPriorities($connectionBugzilla, $clientId)
{
    $movidiusPriorities = getPriorities($connectionBugzilla);
    var_dump($movidiusPriorities);
    foreach ($movidiusPriorities as $priority) {
        IssueSettings::create(
            'issue_priority',
            $clientId,
            $priority['value'],
            '',
            'generic.png',
            '',
            Util::getCurrentDateTime()
        );
    }

    return $movidiusPriorities;
}