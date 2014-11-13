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
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class PlanController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $menuSelectedCategory = 'agile';
        $clientSettings = $this->getRepository(UbirimiClient::class)->getSettings($session->get('client/id'));
        $boardId = $request->get('id');
        $searchQuery = $request->get('q');
        $onlyMyIssuesFlag = $request->query->has('only_my') ? 1 : 0;

        $session->set('last_selected_board_id', $boardId);
        $board = $this->getRepository(Board::class)->getById($boardId);

        if ($board['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $sprintsNotStarted = $this->getRepository(Sprint::class)->getNotStarted($boardId);

        $boardProjects = $this->getRepository(Board::class)->getProjects($boardId, 'array');
        $currentStartedSprint = $this->getRepository(Sprint::class)->getStarted($boardId);
        $lastCompletedSprint = $this->getRepository(Sprint::class)->getLastCompleted($boardId);

        $lastColumn = $this->getRepository(Board::class)->getLastColumn($boardId);
        $completeStatuses = $this->getRepository(Board::class)->getColumnStatuses($lastColumn['id'], 'array', 'id');

        $columns = array('type', 'code', 'summary', 'priority');

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / '
            . SystemProduct::SYS_PRODUCT_CHEETAH_NAME
            . ' / Board: '
            . $board['name']
            . ' / Plan View';

        return $this->render(__DIR__ . '/../../Resources/views/board/Plan.php', get_defined_vars());
    }
}
