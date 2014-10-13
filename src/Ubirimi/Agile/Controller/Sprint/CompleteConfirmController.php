<?php

namespace Ubirimi\Agile\Controller\Sprint;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Agile\Repository\Sprint;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Agile\Repository\Board;

class CompleteConfirmController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $sprintId = $request->get('id');
        $boardId = $request->get('board_id');

        $sprint = Sprint::getById($sprintId);
        $lastColumn = $this->getRepository('agile.board.board')->getLastColumn($boardId);
        $completeStatuses = $this->getRepository('agile.board.board')->getColumnStatuses($lastColumn['id'], 'array', 'id');

        $issuesInSprintCount = Sprint::getSprintIssuesCount($sprintId);
        $completedIssuesInSprint = Sprint::getCompletedIssuesCountBySprintId($sprintId, $completeStatuses);

        return $this->render(__DIR__ . '/../../Resources/views/sprint/CompleteConfirm.php', get_defined_vars());
    }
}
