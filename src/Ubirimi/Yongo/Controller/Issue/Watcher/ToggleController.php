<?php
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\Watcher;
    use Ubirimi\Yongo\Repository\Issue\Issue;

    Util::checkUserIsLoggedInAndRedirect();

    $action = $_POST['action'];
    $issueId = $_POST['id'];

    $currentDate = Util::getServerCurrentDateTime();

    if ($action == 'add') {
        Watcher::addWatcher($issueId, $loggedInUserId, $currentDate);
    } else if ($action == 'remove') {
        Watcher::deleteByUserIdAndIssueId($issueId, $loggedInUserId);
    }

    // update the date_updated field
    Issue::updateById($issueId, array('date_updated' => $currentDate), $currentDate);