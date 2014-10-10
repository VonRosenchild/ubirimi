<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Comment;

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
    UbirimiContainer::getRepository('yongo.issue.comment')->add($issueId, $loggedInUserId, $comment, $currentDate);
}