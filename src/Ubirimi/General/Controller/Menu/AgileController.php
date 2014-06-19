<?php
    use Ubirimi\Util;
    use Ubirimi\Agile\Repository\AgileBoard;

    Util::checkUserIsLoggedInAndRedirect();
    $last5Board = AgileBoard::getLast5BoardsByClientId($clientId);
    $recentBoard = null;
    if ($session->has('last_selected_board_id')) {
        $recentBoard = AgileBoard::getById($session->has('last_selected_board_id'));
    }

    $clientAdministratorFlag = $session->get('user/client_administrator_flag');

    require_once __DIR__ . '/../../Resources/views/menu/Agile.php';
