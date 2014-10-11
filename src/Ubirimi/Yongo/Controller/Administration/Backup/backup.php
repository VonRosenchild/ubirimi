<?php
    use Ubirimi\Repository\Client;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\Settings;

    Util::checkUserIsLoggedInAndRedirect();
    // start the backup procedure

    $statuses = Settings::getAllIssueSettings('status', $clientId, 'array');
    $statusesData = array('issue_status' => $statuses);

    $priorities = Settings::getAllIssueSettings('priority', $clientId, 'array');
    $prioritiesData = array('issue_priority' => $priorities);

    $resolutions = Settings::getAllIssueSettings('resolution', $clientId, 'array');
    $resolutionsData = array('issue_resolution' => $resolutions);

    $users = Client::getUsers($clientId, null, 'array');
    $usersData = array('user' => $users);

    $data = json_encode(array($statusesData, $prioritiesData, $resolutionsData, $usersData));

    $fileName = 'backup/ubirimi_backup_' . date('Y-m-d');
    file_put_contents('./../../' . $fileName . '.bkp', $data);
    Util::createZip(array('./../../' . $fileName . '.bkp'), './../../' . $fileName . '.zip', true);
    echo '<div>Your current backup file is: </div>';
    echo '<a href="./../../' . $fileName . '.zip">' . str_replace('backup/', '', $fileName) . '.zip</a>';