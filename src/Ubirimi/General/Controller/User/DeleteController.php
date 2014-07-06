<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\Repository\User\User;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $userId = $_POST['user_id'];
    $user = User::getById($userId);

    User::deleteById($userId);

    // todo: delete the avatar, if any

    $currentDate = Util::getServerCurrentDateTime();
    Log::add($clientId, SystemProduct::SYS_PRODUCT_GENERAL_SETTINGS, $loggedInUserId, 'DELETE User ' . $user['username'], $currentDate);