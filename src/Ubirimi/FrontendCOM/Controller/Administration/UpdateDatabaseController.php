<?php
    use Ubirimi\Repository\Client;
    use Ubirimi\Repository\User\User;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Permission\PermissionScheme;
    use Ubirimi\Yongo\Repository\Permission\PermissionRole;
    use Ubirimi\Yongo\Repository\Permission\Permission;
    use Ubirimi\Yongo\Repository\Issue\IssueEvent;
    use Ubirimi\Yongo\Repository\Issue\IssueHistory;

    Util::checkSuperUserIsLoggedIn();

    $clients = Client::getAll();

    $date = Util::getServerCurrentDateTime();
    $currentDate = Util::getServerCurrentDateTime();

    $history = IssueHistory::getAll();

    $clientId = 1936;

    while ($record = $history->fetch_array(MYSQLI_ASSOC)) {
        if ($record['field'] == 'assignee') {
            $oldUser = User::getByClientIdAndFullName($clientId, $record['old_value']);
            $newUser = User::getByClientIdAndFullName($clientId, $record['new_value']);

            IssueHistory::updateChangedIds($record['id'], $oldUser['id'], $newUser['id']);
        }
    }

