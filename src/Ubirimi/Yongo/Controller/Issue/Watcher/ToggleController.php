<?php
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueWatcher;

    Util::checkUserIsLoggedInAndRedirect();

    $action = $_POST['action'];
    $issueId = $_POST['id'];

    if ($action == 'add') {
        $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));

        IssueWatcher::addWatcher($issueId, $loggedInUserId, $currentDate);
    } else if ($action == 'remove') {
        IssueWatcher::deleteByUserIdAndIssueId($issueId, $loggedInUserId);
    }