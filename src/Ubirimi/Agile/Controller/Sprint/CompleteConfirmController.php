<?php

namespace Ubirimi\Agile\Controller\Sprint;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class CompleteConfirmController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $sprintId = $request->get('id');
        $boardId = $request->get('board_id');

        $sprint = $this->getRepository('agile.sprint.sprint')->getById($sprintId);
        $lastColumn = $this->getRepository('agile.board.board')->getLastColumn($boardId);
        $completeStatuses = $this->getRepository('agile.board.board')->getColumnStatuses($lastColumn['id'], 'array', 'id');

        $issuesInSprintCount = $this->getRepository('agile.sprint.sprint')->getSprintIssuesCount($sprintId);
        $completedIssuesInSprint = $this->getRepository('agile.sprint.sprint')->getCompletedIssuesCountBySprintId($sprintId, $completeStatuses);

        return $this->render(__DIR__ . '/../../Resources/views/sprint/CompleteConfirm.php', get_defined_vars());
    }
}
