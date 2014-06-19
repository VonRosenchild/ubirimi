<?php

namespace Ubirimi\Yongo\Controller\Report;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Agile\Repository\AgileBoard;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class DeleteConfirmController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $filterId = $request->get('id');
        $deletePossible = $request->get('possible');

        if ($deletePossible) {
            return new Response('Are you sure you want to delete this report?');
        }

        $boards = AgileBoard::getByFilterId($filterId);

        $message = 'This report can not be deleted due to the following reasons:';
        $message .= '<br />';

        if ($boards) {
            $message .= 'It is used in the following agile boards: ';
            $boardsName = array();
            while ($board = $boards->fetch_array(MYSQLI_ASSOC)) {
                $boardsName[] = $board['name'];
            }

            $message .= implode(', ', $boardsName) . '.';
        }

        return new Response($message);
    }
}
