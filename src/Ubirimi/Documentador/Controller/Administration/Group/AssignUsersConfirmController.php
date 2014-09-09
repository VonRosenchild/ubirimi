<?php
    use Ubirimi\Repository\Client;
    use Ubirimi\Repository\Group\Group;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $groupId = $_GET['id'];

    $group = Group::getMetadataById($groupId);
    $allUsers = Client::getUsers($clientId);
    $groupUsers = Group::getDataByGroupId($groupId);

    $groupUsersArrayIds = array();

    while ($groupUsers && $user = $groupUsers->fetch_array(MYSQLI_ASSOC))
        $groupUsersArrayIds[] = $user['user_id'];
    if ($groupUsers)
        $groupUsers->data_seek(0);

    $firstSelected = true;

    require_once __DIR__ . '/../../../Resources/views/administration/group/AssignUsersConfirm.php';