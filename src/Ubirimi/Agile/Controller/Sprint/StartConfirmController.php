<?php

namespace Ubirimi\Agile\Controller\Sprint;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Agile\Repository\Sprint;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class StartConfirmController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $sprintId = $request->get('id');
        $sprint = Sprint::getById($sprintId);
        $today = date("Y-m-d");
        $todayPlus2Weeks = date('Y-m-d', strtotime('+2 week', strtotime($today)));

        return $this->render(__DIR__ . '/../../Resources/views/sprint/StartConfirm.php', get_defined_vars());
    }
}
