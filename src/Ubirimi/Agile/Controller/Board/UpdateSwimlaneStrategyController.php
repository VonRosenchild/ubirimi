<?php

namespace Ubirimi\Agile\Controller\Board;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Agile\Repository\Board;
use Ubirimi\Util;
use Ubirimi\UbirimiController;

class UpdateSwimlaneStrategyController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $boardId = $request->request->get('id');
        $strategy = $request->request->get('strategy');

        $this->getRepository('agile.board.board')->updateSwimlaneStrategy($boardId, $strategy);

        return new Response('');
    }
}
