<?php
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueWatcher;

    Util::checkUserIsLoggedInAndRedirect();

    $userIds = $_POST['id'];
    $issueId = $_POST['issue_id'];

    $currentDate = Util::getServerCurrentDateTime();
    if ($userIds) {
        for ($i = 0; $i < count($userIds); $i++) {
            IssueWatcher::addWatcher($issueId, $userIds[$i], $currentDate);
        }
    }
