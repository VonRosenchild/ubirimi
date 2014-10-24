<?php

namespace Ubirimi\Agile\Controller\Board\Column;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Agile\Repository\Board\Board;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class UpdatePositionController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $newOrder = $request->request->get('order');

        $this->getRepository(Board::class)->updateColumnOrder($newOrder);

        return new Response('');
    }
}
