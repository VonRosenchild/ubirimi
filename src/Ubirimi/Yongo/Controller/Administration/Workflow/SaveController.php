<?php

namespace Ubirimi\Yongo\Controller\Administration\Workflow;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Workflow\Position;

class SaveController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $Id = $request->request->get('id');
        $positions = $request->request->get('positions');

        $good_positions = array();
        for ($i = 0; $i < count($positions); $i++) {
            $values = explode('###', $positions[$i]);
            $good_positions[] = $values;
        }

        Position::deleteByWorkflowId($Id);

        Position::addPosition($Id, $good_positions);

        return new Response('');
    }
}
