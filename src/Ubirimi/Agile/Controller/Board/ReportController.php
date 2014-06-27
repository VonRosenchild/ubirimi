<?php

namespace Ubirimi\Agile\Controller\Board;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Agile\Repository\AgileBoard;
use Ubirimi\Agile\Repository\AgileSprint;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class ReportController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $menuSelectedCategory = 'agile';

        $sprintId = $request->get('id');
        $boardId = $request->get('board_id');
        $chart = $request->get('chart');

        $board = AgileBoard::getById($boardId);

        if ($board['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $currentStartedSprint = AgileSprint::getStarted($boardId);

        if ($sprintId != -1) {
            $selectedSprint = AgileSprint::getById($sprintId);
            $completedSprints = AgileSprint::getCompleted($boardId, $sprintId);
            $doneIssues = AgileSprint::getCompletedUncompletedIssuesBySprintId($sprintId, 1);
            $notDoneIssues = AgileSprint::getCompletedUncompletedIssuesBySprintId($sprintId, 0);
        }

        $availableCharts = array('sprint_report' => 'Sprint Report', 'velocity_chart' => 'Velocity Chart');

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / '
            . SystemProduct::SYS_PRODUCT_CHEETAH_NAME
            . ' / Board: '
            . $board['name']
            . ' / Report View';

        return $this->render(__DIR__ . '/../../Resources/views/board/Report.php', get_defined_vars());
    }
}
