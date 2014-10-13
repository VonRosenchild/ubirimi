<?php
    use Ubirimi\Repository\Group\Group;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $groupId = $_POST['group_id'];
    $userArray = $_POST['user_arr'];
    $this->getRepository('ubirimi.user.group')->deleteDataByGroupId($groupId);

    $currentDate = Util::getServerCurrentDateTime();
    $this->getRepository('ubirimi.user.group')->addData($groupId, $userArray, $currentDate);