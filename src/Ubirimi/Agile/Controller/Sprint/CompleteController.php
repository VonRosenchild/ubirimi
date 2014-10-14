<?php

namespace Ubirimi\Agile\Controller\Sprint;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use Ubirimi\UbirimiController;
use Ubirimi\Util;


class CompleteController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $sprintId = $request->request->get('id');
        $boardId = $request->request->get('board_id');

        $sprint = Sprint::getById($sprintId);
        $lastColumn = $this->getRepository('agile.board.board')->getLastColumn($boardId);
        $completeStatuses = $this->getRepository('agile.board.board')->getColumnStatuses($lastColumn['id'], 'array', 'id');

        $this->getRepository('agile.board.board')->transferNotDoneIssues($boardId, $sprintId, $completeStatuses);

        Sprint::complete($sprintId);

        return new Response('');
    }
}
