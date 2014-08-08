<?php
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueWatcher;
    use Ubirimi\Yongo\Repository\Issue\Issue;

    Util::checkUserIsLoggedInAndRedirect();

    $action = $_POST['action'];
    $issueId = $_POST['id'];

    $currentDate = Util::getServerCurrentDateTime();

    if ($action == 'add') {
        IssueWatcher::addWatcher($issueId, $loggedInUserId, $currentDate);
    } else if ($action == 'remove') {
        IssueWatcher::deleteByUserIdAndIssueId($issueId, $loggedInUserId);
    }

    // update the date_updated field
    Issue::updateById($issueId, array('date_updated' => $currentDate), $currentDate);