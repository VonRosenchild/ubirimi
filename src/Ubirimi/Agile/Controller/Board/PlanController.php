<?php

namespace Ubirimi\Agile\Controller\Board;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class PlanController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $menuSelectedCategory = 'agile';
        $clientSettings = $this->getRepository('ubirimi.general.client')->getSettings($session->get('client/id'));
        $boardId = $request->get('id');
        $searchQuery = $request->get('q');
        $onlyMyIssuesFlag = $request->query->has('only_my') ? 1 : 0;

        $session->set('last_selected_board_id', $boardId);
        $board = $this->getRepository('agile.board.board')->getById($boardId);

        if ($board['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $sprintsNotStarted = $this->getRepository('agile.sprint.sprint')->getNotStarted($boardId);

        $boardProjects = $this->getRepository('agile.board.board')->getProjects($boardId, 'array');
        $currentStartedSprint = $this->getRepository('agile.sprint.sprint')->getStarted($boardId);
        $lastCompletedSprint = $this->getRepository('agile.sprint.sprint')->getLastCompleted($boardId);

        $lastColumn = $this->getRepository('agile.board.board')->getLastColumn($boardId);
        $completeStatuses = $this->getRepository('agile.board.board')->getColumnStatuses($lastColumn['id'], 'array', 'id');

        $columns = array('type', 'code', 'summary', 'priority');

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / '
            . SystemProduct::SYS_PRODUCT_CHEETAH_NAME
            . ' / Board: '
            . $board['name']
            . ' / Plan View';

        return $this->render(__DIR__ . '/../../Resources/views/board/Plan.php', get_defined_vars());
    }
}
