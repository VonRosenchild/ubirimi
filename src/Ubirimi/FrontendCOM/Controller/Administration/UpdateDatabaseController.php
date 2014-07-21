<?php
    use Ubirimi\Repository\Client;
    use Ubirimi\Repository\User\User;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Permission\PermissionScheme;
    use Ubirimi\Yongo\Repository\Permission\PermissionRole;
    use Ubirimi\Yongo\Repository\Permission\Permission;
    use Ubirimi\Yongo\Repository\Issue\IssueEvent;
    Util::checkSuperUserIsLoggedIn();

    $clients = Client::getAll();

    $date = Util::getServerCurrentDateTime();
    $currentDate = Util::getServerCurrentDateTime();

    while ($client = $clients->fetch_array(MYSQLI_ASSOC)) {
        $clientId = $client['id'];

        $currentDate = Util::getServerCurrentDateTime();
        $eventId = IssueEvent::addRaw($clientId, 'Issue Moved', 13, "This is the 'issue moved' event.", 1, $currentDate);
    }