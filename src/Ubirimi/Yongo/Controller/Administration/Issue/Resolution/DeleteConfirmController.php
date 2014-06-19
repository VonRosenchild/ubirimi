<?php
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueSettings;

    Util::checkUserIsLoggedInAndRedirect();

    $Id = $_GET['id'];

    $resolutions = IssueSettings::getAllIssueSettings('resolution', $clientId);

    require_once __DIR__ . '/../../../../Resources/views/administration/issue/resolution/DeleteConfirm.php';