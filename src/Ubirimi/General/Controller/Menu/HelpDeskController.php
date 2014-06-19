<?php
    use Ubirimi\Repository\Client;
    use Ubirimi\Util;

    if (Util::checkUserIsLoggedIn()) {

        $selectedProjectId = $session->get('selected_project_id');
    } else {
        $httpHOST = Util::getHttpHost();
        $clientId = Client::getByBaseURL($httpHOST, 'array', 'id');
        $loggedInUserId = null;
        $selectedProjectId = null;
    }

    $clientAdministratorFlag = $session->get('user/client_administrator_flag');
    require_once __DIR__ . '/../../Resources/views/menu/HelpDesk.php';