<?php
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueComment;

    Util::checkUserIsLoggedInAndRedirect();

    $issueId = isset($_POST['issue_id']) ? $_POST['issue_id'] : null;
    $attachIdsToBeKept = $_POST['attach_ids'];
    $comment = Util::cleanRegularInputField($_POST['comment']);

    if (!is_array($attachIdsToBeKept)) {
        $attachIdsToBeKept = array();
    }

    Util::manageModalAttachments($issueId, $loggedInUserId, $attachIdsToBeKept);

    if (!empty($comment)) {
        $currentDate = Util::getServerCurrentDateTime();
        IssueComment::add($issueId, $loggedInUserId, $comment, $currentDate);
    }