<?php

use Ubirimi\Yongo\Repository\Issue\Settings;
use Ubirimi\Util;

function installPriorities($connectionBugzilla, $clientId)
{
    $movidiusPriorities = getPriorities($connectionBugzilla);

    foreach ($movidiusPriorities as $priority) {
        Settings::create(
            'issue_priority',
            $clientId,
            $priority['value'],
            '',
            'generic.png',
            '',
            Util::getServerCurrentDateTime()
        );
    }

    return $movidiusPriorities;
}