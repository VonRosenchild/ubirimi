<?php
    use Ubirimi\Repository\Client;
    use Ubirimi\Repository\Group\Group;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $groupId = $_GET['group_id'];

    $group = Group::getMetadataById($groupId);
    $allUsers = Client::getUsers($clientId);
    $groupUsers = Group::getDataByGroupId($groupId);

    $group_users_arr_ids = array();

    while ($groupUsers && $user = $groupUsers->fetch_array(MYSQLI_ASSOC))
        $group_users_arr_ids[] = $user['user_id'];
    if ($groupUsers)
        $groupUsers->data_seek(0);

    $first_selected = true;

    require_once __DIR__ . '/../../../Resources/views/administration/group/AssignUsersConfirm.php';