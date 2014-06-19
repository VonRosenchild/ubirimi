<?php
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueSettings;

    Util::checkUserIsLoggedInAndRedirect();

    $Id = $_GET['id'];

    $priorities = IssueSettings::getAllIssueSettings('priority', $clientId);

    require_once __DIR__ . '/../../../../Resources/views/administration/issue/priority/DeleteConfirm.php';