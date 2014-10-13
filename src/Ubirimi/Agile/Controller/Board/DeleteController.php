<?php

namespace Ubirimi\Agile\Controller\Board;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Agile\Repository\Board;
use Ubirimi\Repository\Log;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class DeleteController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $boardId = $request->request->get('id');
        $board = $this->getRepository('agile.board.board')->getById($boardId);

        $this->getRepository('agile.board.board')->deleteById($boardId);

        $date = Util::getServerCurrentDateTime();
        $this->getRepository('ubirimi.general.log')->add(
            $session->get('client/id'),
            SystemProduct::SYS_PRODUCT_CHEETAH,
            $session->get('user/id'),
            'DELETE Cheetah Agile Board ' . $board['name'],
            $date
        );

        return new Response('');
    }
}
