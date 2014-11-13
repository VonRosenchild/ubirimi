<?php

/*
 *  Copyright (C) 2012-2014 SC Ubirimi SRL <info-copyright@ubirimi.com>
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License version 2 as
 *  published by the Free Software Foundation.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301, USA.
 */

namespace Ubirimi\Agile\Controller\Board;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Agile\Repository\Board\Board;
use Ubirimi\Agile\Repository\Sprint\Sprint;
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
