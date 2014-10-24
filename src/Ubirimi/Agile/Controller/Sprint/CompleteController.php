<?php

namespace Ubirimi\Agile\Controller\Sprint;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Agile\Repository\Board\Board;
use Ubirimi\Agile\Repository\Sprint\Sprint;
use Ubirimi\UbirimiController;
use Ubirimi\Util;


class CompleteController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $sprintId = $request->request->get('id');
        $boardId = $request->request->get('board_id');

        $sprint = $this->getRepository(Sprint::class)->getById($sprintId);
        $lastColumn = $this->getRepository(Board::class)->getLastColumn($boardId);
        $completeStatuses = $this->getRepository(Board::class)->getColumnStatuses($lastColumn['id'], 'array', 'id');

        $this->getRepository(Board::class)->transferNotDoneIssues($boardId, $sprintId, $completeStatuses);

        $this->getRepository(Sprint::class)->complete($sprintId);

        return new Response('');
    }
}
