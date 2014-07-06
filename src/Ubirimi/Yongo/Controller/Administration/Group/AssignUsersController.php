<?php
    use Ubirimi\Repository\Group\Group;
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $groupId = $_POST['group_id'];
    $userArray = $_POST['user_arr'];

    $group = Group::getMetadataById($groupId);
    Group::deleteDataByGroupId($groupId);

    $currentDate = Util::getServerCurrentDateTime();
    Group::addData($groupId, $userArray, $currentDate);

    $date = Util::getServerCurrentDateTime();
    Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'UPDATE Yongo Group Members ' . $group['name'], $date);