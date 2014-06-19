<?php
    use Ubirimi\Repository\Group\Group;
    use Ubirimi\Repository\User\User;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $userId = $_GET['user_id'];
    $productId = $_GET['product_id'];

    $user = User::getById($userId);
    $allProductGroups = Group::getByClientIdAndProductId($clientId, $productId);
    $userGroups = Group::getByUserIdAndProductId($userId, $productId);

    $user_groups_ids_arr = array();

    while ($userGroups && $group = $userGroups->fetch_array(MYSQLI_ASSOC))
        $user_groups_ids_arr[] = $group['id'];

    if ($userGroups)
        $userGroups->data_seek(0);

    $first_selected = true;

    require_once __DIR__ . '/../../../Resources/views/administration/user/AssignGroupsConfirm.php';