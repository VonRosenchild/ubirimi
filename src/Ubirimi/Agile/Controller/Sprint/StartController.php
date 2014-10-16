<?php

namespace Ubirimi\Agile\Controller\Sprint;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use Ubirimi\UbirimiController;
use Ubirimi\Util;

class StartController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $sprintId = $request->request->get('id');
        $startDate = $request->request->get('start_date');
        $endDate = $request->request->get('end_date');
        $name = $request->request->get('name');

        $this->getRepository('agile.sprint.sprint')->start($sprintId, $startDate, $endDate, $name);

        return new Response('');
    }
}
