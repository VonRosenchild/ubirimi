<?php
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\Issue;

    Util::checkUserIsLoggedInAndRedirect();

    $issueId = $_POST['issue_id'];
    $issueTypeId = $_POST['issue_type_id'];
    $issueData = Issue::getByParameters(array('issue_id' => $issueId), $loggedInUserId);

    require_once __DIR__ . '/../../Resources/views/issue/ViewEditDialog.php';