<?php

namespace Ubirimi\Agile\Controller\Board\Column;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Agile\Repository\AgileBoard;
use Ubirimi\Util;

class AddController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $boardId = $request->request->get('board_id');
        $name = $request->request->get('name');
        $description = $request->request->get('description');

        AgileBoard::addColumn($boardId, $name, $description);

        return new Response('');
    }
}
