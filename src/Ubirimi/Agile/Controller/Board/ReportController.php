<?php

namespace Ubirimi\Agile\Controller\Board;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Agile\Repository\Board\Board;
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

        $board = $this->getRepository(Board::class)->getById($boardId);

        if ($board['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $currentStartedSprint = $this->getRepository(Sprint::class)->getStarted($boardId);

        if ($sprintId != -1) {
            $selectedSprint = $this->getRepository(Sprint::class)->getById($sprintId);
            $completedSprints = $this->getRepository(Sprint::class)->getCompleted($boardId, $sprintId);
            $doneIssues = $this->getRepository(Sprint::class)->getCompletedUncompletedIssuesBySprintId($sprintId, 1);
            $notDoneIssues = $this->getRepository(Sprint::class)->getCompletedUncompletedIssuesBySprintId($sprintId, 0);
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
