<?php

namespace Ubirimi\General\Controller\Menu;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;

class AgileController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $last5Board = $this->getRepository('agile.board.board')->getLast5BoardsByClientId($session->get('client/id'));
        $recentBoard = null;

        if ($session->has('last_selected_board_id')) {
            $recentBoard = $this->getRepository('agile.board.board')->getById($session->has('last_selected_board_id'));
        }

        $clientAdministratorFlag = $session->get('user/client_administrator_flag');

        return $this->render(__DIR__ . '/../../Resources/views/menu/Agile.php', get_defined_vars());
    }
}
