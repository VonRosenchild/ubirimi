<?php
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\Watcher;
    use Ubirimi\Yongo\Repository\Issue\Issue;

    Util::checkUserIsLoggedInAndRedirect();

    $userIds = $_POST['id'];
    $issueId = $_POST['issue_id'];

    $currentDate = Util::getServerCurrentDateTime();
    if ($userIds) {
        for ($i = 0; $i < count($userIds); $i++) {
            Watcher::addWatcher($issueId, $userIds[$i], $currentDate);
        }

        // update the date_updated field
        Issue::updateById($issueId, array('date_updated' => $currentDate), $currentDate);
    }