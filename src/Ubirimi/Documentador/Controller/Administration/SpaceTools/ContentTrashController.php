<?php

namespace Ubirimi\Documentador\Controller\Administration\SpaceTools;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class ContentTrashController extends UbirimiController
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
        $clientSettings = $this->getRepository('ubirimi.general.client')->getSettings($clientId);

        $deletedPages = $this->getRepository('documentador.space.space')->getDeletedPages($spaceId);

        require_once __DIR__ . '/../../../Resources/views/administration/spacetools/ContentTrash.php';
    }
}
