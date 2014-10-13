<?php

namespace Ubirimi\General\Controller\Menu;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Repository\Client;
use Ubirimi\Util;

class HelpDeskController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        if (Util::checkUserIsLoggedIn()) {

            $selectedProjectId = $session->get('selected_project_id');
        } else {
            $httpHOST = Util::getHttpHost();
            $clientId = $this->getRepository('ubirimi.general.client')->getByBaseURL($httpHOST, 'array', 'id');
            $loggedInUserId = null;
            $selectedProjectId = null;
        }

        $clientAdministratorFlag = $session->get('user/client_administrator_flag');

        return $this->render(__DIR__ . '/../../Resources/views/menu/HelpDesk.php', get_defined_vars());
    }
}
