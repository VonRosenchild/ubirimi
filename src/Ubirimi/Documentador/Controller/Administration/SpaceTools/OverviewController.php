<?php

namespace Ubirimi\Documentador\Controller\Administration\SpaceTools;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Documentador\Repository\Space\Space;
use Ubirimi\Documentador\Repository\Entity\Entity;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class OverviewController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientId = $session->get('client/id');

        $menuSelectedCategory = 'doc_spaces';

        $spaceId = $request->get('id');
        $space = $this->getRepository('documentador.space.space')->getById($spaceId);

        if ($space['client_id'] != $clientId) {
            header('Location: /general-settings/bad-link-access-denied');
            die();
        }

        require_once __DIR__ . '/../../../Resources/views/administration/spacetools/Overview.php';
    }
}

