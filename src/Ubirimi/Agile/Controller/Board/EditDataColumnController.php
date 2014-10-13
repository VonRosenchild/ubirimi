<?php

namespace Ubirimi\Agile\Controller\Board;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Agile\Repository\Board;
use Ubirimi\SystemProduct;
use Ubirimi\Util;
use Ubirimi\UbirimiController;

class EditDataColumnController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $menuSelectedCategory = 'agile';

        $boardId = $request->get('id');
        $board = $this->getRepository('agile.board.board')->getById($boardId);

        if ($board['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $columns = $this->getRepository('agile.board.board')->getColumns($boardId, 'array');

        $columnWidth = 100 / (count($columns) + 1);
        $unmappedStatuses = $this->getRepository('agile.board.board')->getUnmappedStatuses($session->get('client/id'), $boardId, 'array');

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / '
            . SystemProduct::SYS_PRODUCT_CHEETAH_NAME
            . ' / Board / Manage Columns';

        return $this->render(__DIR__ . '/../../Resources/views/board/EditDataColumn.php', get_defined_vars());
    }
}
