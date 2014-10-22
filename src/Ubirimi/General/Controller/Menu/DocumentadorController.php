<?php

namespace Ubirimi\General\Controller\Menu;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class DocumentadorController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        if (Util::checkUserIsLoggedIn()) {
            Util::checkUserIsLoggedInAndRedirect();

            $spaces = $this->getRepository('documentador.space.space')->getByClientId($session->get('client/id'));
        } else {
            $httpHOST = Util::getHttpHost();
            $clientId = $this->getRepository('ubirimi.general.client')->getByBaseURL($httpHOST, 'array', 'id');
            $spaces = $this->getRepository('documentador.space.space')->getByClientIdAndAnonymous($clientId);
        }

        return $this->render(__DIR__ . '/../../Resources/views/menu/Documentador.php', get_defined_vars());
    }
}
