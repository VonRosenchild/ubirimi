<?php
    use Ubirimi\Repository\Client;
    use Ubirimi\Repository\Documentador\Space;
    use Ubirimi\Util;

    if (Util::checkUserIsLoggedIn()) {
        Util::checkUserIsLoggedInAndRedirect();

        $spaces = Space::getByClientId($clientId);
    } else {
        $httpHOST = Util::getHttpHost();
        $clientId = Client::getByBaseURL($httpHOST, 'array', 'id');
        $spaces = Space::getByClientIdAndAnonymous($clientId);
    }

    require_once __DIR__ . '/../../Resources/views/menu/Documentator.php';