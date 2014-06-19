<?php
    use Ubirimi\Repository\HelpDesk\SLA;
    use Ubirimi\Repository\User\User;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueSettings;
    use Ubirimi\Yongo\Repository\Issue\IssueType;

    Util::checkUserIsLoggedInAndRedirect();

    $key = $_GET['term'];
    $projectId = $_GET['project_id'];

    $explodeCriteria = array('=', '(', ')');
    for ($i = 0; $i < count($explodeCriteria); $i++) {
        $keyParts = explode('=', $key);
        $key = end($keyParts);
    }

    $standardKeyWords = array('priority', 'status', 'resolution', 'type', 'assignee', 'reporter', 'currentUser()', 'AND', 'OR', 'NOT IN', 'IN', 'Unresolved');
    $SLAs = SLA::getByProjectId($projectId);
    while ($SLAs && $SLA = $SLAs->fetch_array(MYSQLI_ASSOC)) {
        $standardKeyWords[] = $SLA['name'];
    }

    $statuses = IssueSettings::getAllIssueSettings('status', $clientId);
    $priorities = IssueSettings::getAllIssueSettings('priority', $clientId);
    $resolutions = IssueSettings::getAllIssueSettings('resolution', $clientId);
    $types = IssueType::getAll($clientId);
    $users = User::getByClientId($clientId);

    while ($types && $type = $types->fetch_array(MYSQLI_ASSOC)) {
        $standardKeyWords[] = $type['name'];
    }
    while ($statuses && $status = $statuses->fetch_array(MYSQLI_ASSOC)) {
        $standardKeyWords[] = $status['name'];
    }
    while ($priorities && $priority = $priorities->fetch_array(MYSQLI_ASSOC)) {
        $standardKeyWords[] = $priority['name'];
    }
    while ($resolutions && $resolution = $resolutions->fetch_array(MYSQLI_ASSOC)) {
        $standardKeyWords[] = $resolution['name'];
    }
    while ($users && $user = $users->fetch_array(MYSQLI_ASSOC)) {
        $standardKeyWords[] = $user['username'];
    }

    // get last word
    $words = explode(' ', $key);
    $lastWord = $words[count($words) - 1];
    $returnValues = array();
    for ($i = 0; $i < count($standardKeyWords); $i++) {
        if (strpos(mb_strtolower($standardKeyWords[$i]), mb_strtolower($lastWord)) !== false) {
            $returnValues[] = $standardKeyWords[$i];
        }
    }

    echo json_encode($returnValues);