<?php

namespace Ubirimi\Agile\Controller\Sprint;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Agile\Repository\Sprint;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Agile\Repository\Board;

class CompleteController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $sprintId = $request->request->get('id');
        $boardId = $request->request->get('board_id');

        $sprint = Sprint::getById($sprintId);
        $lastColumn = Board::getLastColumn($boardId);
        $completeStatuses = Board::getColumnStatuses($lastColumn['id'], 'array', 'id');

        Board::transferNotDoneIssues($boardId, $sprintId, $completeStatuses);

        Sprint::complete($sprintId);

        return new Response('');
    }
}
