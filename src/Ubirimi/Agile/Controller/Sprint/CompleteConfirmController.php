<?php

namespace Ubirimi\Agile\Controller\Sprint;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Agile\Repository\AgileSprint;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Agile\Repository\AgileBoard;

class CompleteConfirmController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $sprintId = $request->get('id');
        $boardId = $request->get('board_id');

        $sprint = AgileSprint::getById($sprintId);
        $lastColumn = AgileBoard::getLastColumn($boardId);
        $completeStatuses = AgileBoard::getColumnStatuses($lastColumn['id'], 'array', 'id');

        $issuesInSprintCount = AgileSprint::getSprintIssuesCount($sprintId);
        $completedIssuesInSprint = AgileSprint::getCompletedIssuesCountBySprintId($sprintId, $completeStatuses);

        return $this->render(__DIR__ . '/../../Resources/views/sprint/CompleteConfirm.php', get_defined_vars());
    }
}
