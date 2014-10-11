<?php

namespace Ubirimi\Agile\Controller\Board\Column;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Agile\Repository\Board;
use Ubirimi\Util;

class UpdateColumnController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $boardId = $request->request->get('board_id');
        $StatusId = $request->request->get('status_id');
        $newColumnId = $request->request->get('new_column_id');

        Board::deleteStatusFromColumn($boardId, $StatusId);
        if ($newColumnId)
            Board::addStatusToColumn($newColumnId, $StatusId);

        return new Response('');
    }
}
