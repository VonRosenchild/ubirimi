<?php
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueWatcher;
    use Ubirimi\Yongo\Repository\Issue\Issue;

    Util::checkUserIsLoggedInAndRedirect();

    $currentDate = Util::getServerCurrentDateTime();

    $userId = $_POST['id'];
    $issueId = $_POST['issue_id'];

    IssueWatcher::deleteByUserIdAndIssueId($issueId, $userId);

    // update the date_updated field
    Issue::updateById($issueId, array('date_updated' => $currentDate), $currentDate);