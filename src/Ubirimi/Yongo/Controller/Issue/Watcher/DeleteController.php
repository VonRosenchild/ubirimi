<?php
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueWatcher;

    Util::checkUserIsLoggedInAndRedirect();

    $userId = $_POST['id'];
    $issueId = $_POST['issue_id'];

    IssueWatcher::deleteByUserIdAndIssueId($issueId, $userId);