<?php
    use Ubirimi\Agile\Repository\AgileBoard;
    use Ubirimi\Agile\Repository\AgileSprint;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $sprintId = $_POST['id'];
    $issueIdArray = isset($_POST['issue_id']) ? $_POST['issue_id'] : null;

    if ($sprintId && $issueIdArray) {
        AgileBoard::deleteIssuesFromSprints($issueIdArray);
        AgileSprint::addIssues($sprintId, $issueIdArray, $loggedInUserId);
    }