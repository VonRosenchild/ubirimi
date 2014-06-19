<?php
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Project\Project;

    Util::checkUserIsLoggedInAndRedirect();

    $projectId = $_POST['id'];
    $moveToIssueTypes = Project::getIssueTypes($projectId, 'array');

    echo json_encode($moveToIssueTypes);

