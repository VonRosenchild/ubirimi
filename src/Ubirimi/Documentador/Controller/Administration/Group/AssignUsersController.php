<?php
    use Ubirimi\Repository\Group\Group;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $groupId = $_POST['group_id'];
    $userArray = $_POST['user_arr'];
    Group::deleteDataByGroupId($groupId);

    $currentDate = Util::getServerCurrentDateTime();
    Group::addData($groupId, $userArray, $currentDate);