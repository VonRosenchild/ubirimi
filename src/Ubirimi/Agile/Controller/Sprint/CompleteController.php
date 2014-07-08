<?php

namespace Ubirimi\Agile\Controller\Sprint;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Agile\Repository\AgileSprint;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Agile\Repository\AgileBoard;

class CompleteController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $sprintId = $request->request->get('id');
        $boardId = $request->request->get('board_id');

        $sprint = AgileSprint::getById($sprintId);
        $lastColumn = AgileBoard::getLastColumn($boardId);
        $completeStatuses = AgileBoard::getColumnStatuses($lastColumn['id'], 'array', 'id');

        AgileBoard::transferNotDoneIssues($boardId, $sprintId, $completeStatuses);

        AgileSprint::complete($sprintId);

        return new Response('');
    }
}
