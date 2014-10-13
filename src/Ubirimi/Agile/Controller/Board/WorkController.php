<?php

namespace Ubirimi\Agile\Controller\Board;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Agile\Repository\Board;
use Ubirimi\Agile\Repository\Sprint;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class WorkController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $menuSelectedCategory = 'agile';

        $sprintId = $request->get('id');
        $boardId = $request->get('board_id');
        $onlyMyIssuesFlag = $request->query->has('only_my') ? 1 : 0;
        $board = $this->getRepository('agile.board.board')->getById($boardId);

        if ($board['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $swimlaneStrategy = $board['swimlane_strategy'];

        if ($sprintId == -1) {
            $sprint = null;
        } else {
            $sprint = Sprint::getById($sprintId);
            $sprintBoardId = $sprint['agile_board_id'];
            if ($sprintBoardId != $boardId) {
                return new RedirectResponse('/general-settings/bad-link-access-denied');
            }
        }

        $columns = $this->getRepository('agile.board.board')->getColumns($boardId, 'array');
        $lastCompletedSprint = Sprint::getLastCompleted($boardId);

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / '
            . SystemProduct::SYS_PRODUCT_CHEETAH_NAME
            . ' / Board: '
            . $board['name']
            . ' / Work View';

        return $this->render(__DIR__ . '/../../Resources/views/board/Work.php', get_defined_vars());
    }
}
