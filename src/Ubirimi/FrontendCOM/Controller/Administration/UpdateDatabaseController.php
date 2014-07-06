<?php

    use Ubirimi\Repository\Client;
    use Ubirimi\Repository\User\User;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Permission\PermissionScheme;
    use Ubirimi\Yongo\Repository\Permission\PermissionRole;
    use Ubirimi\Yongo\Repository\Permission\Permission;

    Util::checkSuperUserIsLoggedIn();

    $clients = Client::getAll();

    $date = Util::getServerCurrentDateTime();

    $currentDate = Util::getServerCurrentDateTime();

    while ($client = $clients->fetch_array(MYSQLI_ASSOC)) {
        $clientId = $client['id'];

        Client::addProduct($clientId, SystemProduct::SYS_PRODUCT_HELP_DESK, $currentDate);
    }