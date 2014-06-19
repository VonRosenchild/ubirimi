<?php
    use Ubirimi\Repository\User\User;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $userId = $_POST['user_id'];
    $assignedGroups = $_POST['assigned_groups'];

    User::deleteGroupsByUserId($userId);

    if ($assignedGroups != -1) {
        User::addGroups($userId, $assignedGroups);
    }