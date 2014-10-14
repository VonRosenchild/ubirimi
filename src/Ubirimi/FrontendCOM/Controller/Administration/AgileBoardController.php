<?php

namespace Ubirimi\FrontendCOM\Controller\Administration;

use Ubirimi\UbirimiController;
use Ubirimi\Util;


class AgileBoardController extends UbirimiController
{
    public function indexAction()
    {
        Util::checkSuperUserIsLoggedIn();

        $agileBoards = $this->getRepository('agile.board.board')->getAll(array('sort_by' => 'agile_board.date_created', 'sort_order' => 'desc'));

        $selectedOption = 'agile';

        return $this->render(__DIR__ . '/../../Resources/views/administration/AgileBoard.php', get_defined_vars());
    }
}
