<?php

namespace Ubirimi\FrontendCOM\Controller\Administration;

use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Agile\Repository\Board;

class AgileBoardController extends UbirimiController
{
    public function indexAction()
    {
        Util::checkSuperUserIsLoggedIn();

        $agileBoards = Board::getAll(array('sort_by' => 'agile_board.date_created', 'sort_order' => 'desc'));

        $selectedOption = 'agile';

        return $this->render(__DIR__ . '/../../Resources/views/administration/AgileBoard.php', get_defined_vars());
    }
}
